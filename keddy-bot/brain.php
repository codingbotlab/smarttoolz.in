<?php
declare(strict_types=1);

require_once __DIR__ . '/personality.php';
require_once __DIR__ . '/moderation.php';
require_once __DIR__ . '/custom-messages.php';

function brainNormalize(string $text): string
{
    $text = trim($text);
    $text = preg_replace('/https?:\/\/\S+/iu', ' ', $text) ?? $text;
    $text = preg_replace('/(.)\1{5,}/u', '$1$1$1', $text) ?? $text;
    return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
}

function brainTokens(string $text): array
{
    preg_match_all('/[\p{L}\p{N}]{2,}/u', mb_strtolower(brainNormalize($text), 'UTF-8'), $m);
    return array_values(array_unique($m[0] ?? []));
}

function brainHasAny(string $text, array $words): bool
{
    $text = mb_strtolower($text, 'UTF-8');
    foreach ($words as $word) {
        if (str_contains($text, mb_strtolower((string)$word, 'UTF-8'))) {
            return true;
        }
    }
    return false;
}

function brainSimilarity(string $a, string $b): float
{
    $a = mb_strtolower(brainNormalize($a), 'UTF-8');
    $b = mb_strtolower(brainNormalize($b), 'UTF-8');
    if ($a === '' || $b === '') return 0.0;

    similar_text($a, $b, $percent);
    $aa = brainTokens($a);
    $bb = brainTokens($b);
    $union = count(array_unique(array_merge($aa, $bb)));
    $jaccard = $union > 0 ? count(array_intersect($aa, $bb)) / $union : 0.0;
    return max($jaccard, $percent / 100);
}

function brainDetectLanguage(string $text): string
{
    if (preg_match('/[\x{0900}-\x{097F}]/u', $text)) return 'hindi';
    if (brainHasAny($text, ['hai','hain','kaise','kya','kyu','kyun','mujhe','aap','tum','accha','achha','bahut','nahi','nahin','batao','bolo','yaar','dost','kar','karo','hoon','raha','rahi'])) {
        return 'hinglish';
    }
    return 'english';
}

function brainEmotion(string $text): string
{
    $map = [
        'joy' => ['happy','khush','khushi','excited','mast','wow','yay','😍','😊','😂'],
        'sadness' => ['sad','dukhi','udaas','alone','miss','rona','😭','😢','💔'],
        'anger' => ['angry','gussa','irritated','hate'],
        'love' => ['love','love','pyar','pyaar','cute','jaan','❤️','💕'],
        'curiosity' => ['why','how','what','kya','kaise','kyu','kyun','batao'],
        'fun' => ['joke','funny','mazaak','lol','😂']
    ];

    foreach ($map as $emotion => $words) {
        if (brainHasAny($text, $words)) return $emotion;
    }
    return 'neutral';
}

function brainIntent(string $text): string
{
    $groups = [
        'greeting' => ['hello','hi','hey','hii','hlo','namaste','good morning','good evening','good night'],
        'gratitude' => ['thanks','thank you','thanku','shukriya','dhanyavad'],
        'identity' => ['who are you','tum kaun','kaun ho','your name','naam kya','bot ka naam'],
        'mood' => ['how are you','kaise ho','kya haal','mood'],
        'help' => ['help','madad','what can you do','commands'],
        'like' => ['like kar','like karo','like please'],
        'share' => ['share kar','share karo','share please','forward'],
        'time' => ['time kya','timer','next reminder'],
        'repeat' => ['repeat','repeating','same thing','same baat','why are you repeating','phir se wahi','baar baar','bar bar'],
        'moderation' => ['spam','abuse','report','ban him','remove him','gali'],
        'compliment' => ['nice','awesome','amazing','good bot','smart bot','best bot'],
        'question' => ['?','what ','why ','how ','who ','when ','where ','which ','kya ','kyu ','kyun ','kaise ','kab ','kahan ']
    ];

    $lower = mb_strtolower($text, 'UTF-8');
    $best = 'chat';
    $score = 0.0;

    foreach ($groups as $intent => $patterns) {
        foreach ($patterns as $pattern) {
            $current = str_contains($lower, $pattern) ? 1.0 : brainSimilarity($lower, $pattern);
            if ($current > $score && $current >= 0.58) {
                $best = $intent;
                $score = $current;
            }
        }
    }
    return $best;
}

function brainMemory(string $id, string $name, string $text): array
{
    $key = 'brain_memory_' . hash('sha256', $id !== '' ? $id : ($name !== '' ? $name : 'anon'));
    $memory = gj($key, ['name' => $name, 'messages' => []]);
    if (!is_array($memory)) $memory = ['name' => $name, 'messages' => []];

    $memory['name'] = $name;
    $memory['messages'][] = mb_substr(brainNormalize($text), 0, 180, 'UTF-8');
    $memory['messages'] = array_slice(array_values($memory['messages']), -8);
    sj($key, $memory);
    return $memory;
}

function brainRecent(): array
{
    $recent = gj('brain_recent_chat', []);
    return is_array($recent) ? array_slice($recent, -12) : [];
}

function brainRememberRecent(string $name, string $text): void
{
    $recent = brainRecent();
    $recent[] = [
        'name' => mb_substr($name, 0, 60, 'UTF-8'),
        'text' => mb_substr(brainNormalize($text), 0, 220, 'UTF-8'),
        'ts' => time()
    ];
    sj('brain_recent_chat', array_slice($recent, -12));
}

function brainLowValueCooldown(string $id, string $intent, int $seconds = 45): bool
{
    if ($id === '') return false;
    $key = 'brain_low_' . $intent . '_' . hash('sha256', $id);
    $last = (int)gv($key, '0');
    if (time() - $last < $seconds) return true;
    sv($key, (string)time());
    return false;
}

function brainReplyLocal(string $name, string $text, string $lang, string $emotion, string $intent, array $memory): string
{
    $customIntents = ['greeting', 'like', 'share'];
    if (in_array($intent, $customIntents, true)) {
        return keddyCustomMessage($intent, $name);
    }

    $sets = [
        'gratitude' => ['Arre koi baat nahi 😄', 'Anytime ❤️', 'Mention not 😎'],
        'identity' => ['Main Keddy hoon — is live ka chat companion. 😎', 'Keddy Bot BTS yahin hai 😄 chat, masti aur moderation ke liye.'],
        'mood' => ["Main mast hoon 😄 Tumhara mood kaisa chal raha hai?", "All good, {$name}. Tum batao, mood kaisa hai? 😎"],
        'help' => ['Hindi, Hinglish ya English mein normal baat karo. Sawaal, masti aur chat sab chalega 😎'],
        'time' => ['⏱️ Timer active hai 😄', '⏱️ Keddy timer online hai.'],
        'repeat' => ['Haan 😅 pichhli reply repeat ho gayi thi. Ab seedhi baat.', 'Fair point 😄 Repeat mode off.'],
        'compliment' => ['Aise compliments se Keddy ka ego dangerous ho jayega 😂', 'Noted 😎 confidence +7%.'],
        'moderation' => ['Keddy Shield online hai 😎 Spam aur toxic messages rules ke hisaab se handle honge.']
    ];

    if (isset($sets[$intent])) {
        $replies = $sets[$intent];
        return $replies[array_rand($replies)];
    }

    if ($emotion === 'sadness') return "💜 {$name}, mood heavy lag raha hai. Keddy yahin hai — bolna ho to bolo.";
    if ($emotion === 'anger') return "😅 {$name}, gussa samajh aa raha hai. Bas chat ko toxic mat hone dena.";
    if ($emotion === 'love') return 'Acha ji 😄 pyaar wali vibe aa rahi hai.';
    if ($emotion === 'joy' || $emotion === 'fun') return keddyCustomMessage('general', $name);
    if ($intent === 'question') {
        return $lang === 'english'
            ? "Good question, {$name}. Main seedhi useful baat rakhunga 😄"
            : "Accha sawaal hai, {$name}. Keddy seedha jawab dega 😄";
    }
    return keddyCustomMessage('general', $name);
}

function brainModerate(array $x): bool
{
    $text = (string)($x['snippet']['displayMessage'] ?? '');
    $id = (string)($x['authorDetails']['channelId'] ?? '');
    $name = (string)($x['authorDetails']['displayName'] ?? 'viewer');

    if ($text === '' || $id === '' || !modEligible($x)) return false;

    $decision = modClassify($text);
    if (($decision['action'] ?? 'allow') === 'allow') return false;
    $decision = modEscalate($id, $decision);

    try {
        if (function_exists('keddyDataRecordModeration')) {
            keddyDataRecordModeration((string)gv('oauth_channel_id', ''), $x, $decision, (string)gv('live_id', ''), (string)gv('chat_id', ''));
        }

        $messageId = (string)($x['id'] ?? '');
        $action = (string)($decision['action'] ?? 'allow');

        if ($messageId !== '' && in_array($action, ['delete', 'timeout', 'ban'], true)) {
            ytBot('liveChat/messages/' . $messageId, 'DELETE');
        }

        if (in_array($action, ['timeout', 'ban'], true)) {
            $type = $action === 'ban' ? 'permanent' : 'temporary';
            $body = [
                'snippet' => [
                    'liveChatId' => gv('chat_id'),
                    'type' => $type,
                    'bannedUserDetails' => ['channelId' => $id]
                ]
            ];
            if ($type === 'temporary') {
                $body['snippet']['banDurationSeconds'] = (string)($decision['duration'] ?? 300);
            }
            ytBot('liveChat/bans?part=snippet', 'POST', $body);
        }

        if ($action === 'warn') sendBotMsg("⚠️ {$name}, thoda language soft rakho 😄");
        if ($action === 'timeout') sendBotMsg("🔇 {$name}, 5 min cooling break. Chat peaceful rakho.");
        if ($action === 'ban') sendBotMsg("🚫 {$name}, rules break hue — chat se remove kar raha hoon.");
    } catch (Throwable $e) {
        sv('moderation_last_error', $e->getMessage());
    }
    return true;
}

function brainReply(array $x): string
{
    $name = trim((string)($x['authorDetails']['displayName'] ?? '')) ?: 'dost';
    $id = trim((string)($x['authorDetails']['channelId'] ?? ''));
    $text = brainNormalize((string)($x['snippet']['displayMessage'] ?? ''));

    if ($text === '' || brainModerate($x)) return '';

    $lang = brainDetectLanguage($text);
    $emotion = brainEmotion($text);
    $intent = brainIntent($text);

    if (in_array($intent, ['greeting','gratitude','identity','like','share','compliment'], true)) {
        if (brainLowValueCooldown($id, $intent, 45)) return '';
    }

    $memory = brainMemory($id, $name, $text);
    $memory['channel_id'] = $id;
    brainRememberRecent($name, $text);

    $reply = brainReplyLocal($name, $text, $lang, $emotion, $intent, $memory);

    foreach (brainRecent() as $row) {
        if (($row['name'] ?? '') === 'Keddy' && brainSimilarity($reply, (string)($row['text'] ?? '')) > 0.92) {
            $reply = $lang === 'english'
                ? "Got you, {$name}. Let's keep it moving 😄"
                : "Samajh gaya {$name}. Chalo aage badhte hain 😄";
            break;
        }
    }

    brainRememberRecent('Keddy', $reply);
    return $reply;
}
