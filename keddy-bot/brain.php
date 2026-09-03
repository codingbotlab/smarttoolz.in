<?php
declare(strict_types=1);

/** Keddy Brain: Ollama open-weight LLM first, PHP NLP fallback. */
function brainNormalize(string $text): string {
    $text = trim($text);
    $text = preg_replace('/https?:\/\/\S+/iu', ' ', $text) ?? $text;
    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
    return trim($text);
}
function brainTokens(string $text): array {
    $text = mb_strtolower(brainNormalize($text), 'UTF-8');
    preg_match_all('/[\p{L}\p{N}]{2,}/u', $text, $m);
    return array_values(array_unique($m[0] ?? []));
}
function brainHasAny(string $text, array $words): bool {
    $lower = mb_strtolower($text, 'UTF-8');
    foreach ($words as $word) if (mb_strpos($lower, mb_strtolower($word, 'UTF-8')) !== false) return true;
    return false;
}
function brainSimilarity(string $a, string $b): float {
    $a = mb_strtolower(brainNormalize($a), 'UTF-8'); $b = mb_strtolower(brainNormalize($b), 'UTF-8');
    if ($a === '' || $b === '') return 0.0;
    similar_text($a, $b, $pct); $lev = levenshtein(mb_substr($a, 0, 255), mb_substr($b, 0, 255));
    $max = max(mb_strlen($a), mb_strlen($b), 1);
    return max(0.0, min(1.0, (($pct / 100.0) * 0.65) + ((1.0 - min(1.0, $lev / $max)) * 0.35)));
}
function brainDetectLanguage(string $text): string {
    if (brainHasAny($text, ['hai','hain','kaise','kya','kyu','kyun','mujhe','aap','tum','accha','achha','bahut','nahi','nahin','batao','bolo','yaar','dost','kar','karo','raha','rahi'])) return 'hinglish';
    if (preg_match('/[\x{0900}-\x{097F}]/u', $text)) return 'hindi';
    return 'english';
}
function brainSentiment(string $text): array {
    $pos = ['love','lovely','awesome','great','good','nice','amazing','best','happy','khushi','accha','achha','sundar','pyara','mast','shukriya','thanks','thank','❤️','😍','😊','🔥'];
    $neg = ['sad','bad','hate','angry','bura','dukhi','udaas','pareshan','nahi','nahin','bekar','boring','gussa','ro','rona','😢','😭','💔'];
    $lower = mb_strtolower($text, 'UTF-8'); $p = 0; $n = 0;
    foreach ($pos as $w) if (mb_strpos($lower, mb_strtolower($w, 'UTF-8')) !== false) $p++;
    foreach ($neg as $w) if (mb_strpos($lower, mb_strtolower($w, 'UTF-8')) !== false) $n++;
    $score = $p - $n; return ['label' => $score > 0 ? 'positive' : ($score < 0 ? 'negative' : 'neutral'), 'score' => $score];
}
function brainEmotion(string $text): string {
    $sets = [
        'joy' => ['happy','khush','khushi','excited','awesome','mast','yay','😍','😊','😂'],
        'sadness' => ['sad','dukhi','udaas','alone','akela','miss','rona','ro rahi','😭','😢','💔'],
        'anger' => ['angry','gussa','hate','irritated','chidh','pareshan'],
        'love' => ['love','pyar','pyaar','crush','cute','jaan','❤️','💕'],
        'curiosity' => ['why','how','what','kya','kaise','kyu','kyun','batao'],
    ];
    foreach ($sets as $emotion => $words) if (brainHasAny($text, $words)) return $emotion;
    return 'neutral';
}
function brainIntent(string $text): string {
    $catalog = [
        'greeting' => ['hello','hi','hey','namaste','hii','good morning','good evening','good night','hlo'],
        'gratitude' => ['thanks','thank you','shukriya','dhanyavad','thanku'],
        'like' => ['like kar','like please','like karo','likes','thumbs up'],
        'share' => ['share kar','share karo','share please','forward'],
        'help' => ['help','madad','kya karte ho','what can you do','commands'],
        'time' => ['time kya','kitna time','timer','kab next','next reminder'],
        'identity' => ['tum kaun','who are you','kaun ho','your name','naam kya','bot ka naam'],
        'status' => ['status','online ho','alive ho','chal raha','working'],
        'mood' => ['kaise ho','how are you','kya haal','mood'],
        'compliment' => ['nice','awesome','amazing','accha bot','good bot','smart bot'],
        'confusion' => ['samajh nahi','samajh nhi','confused','what do you mean','matlab'],
        'repeat' => ['repeat','repeating','same thing','same baat','why are you repeating','phir se wahi','baar baar','bar bar','copy what i said'],
        'question' => ['?','kya ','kyu ','kyun ','why ','how ','what ','who ','kab ','where ','where are '],
    ];
    $lower = mb_strtolower($text, 'UTF-8'); $best = 'chat'; $bestScore = 0.0;
    foreach ($catalog as $intent => $phrases) foreach ($phrases as $phrase) {
        if (mb_strpos($lower, mb_strtolower($phrase, 'UTF-8')) !== false) {
            $score = min(1.0, 0.55 + (mb_strlen($phrase) / max(mb_strlen($lower), 1)) * 0.45);
            if ($score > $bestScore) { $best = $intent; $bestScore = $score; }
        } else {
            $score = brainSimilarity($lower, $phrase) * 0.75;
            if ($score > $bestScore && $score >= 0.68) { $best = $intent; $bestScore = $score; }
        }
    }
    return $best;
}
function brainRemember(string $authorId, string $name, string $text): array {
    $key = 'brain_memory_' . hash('sha256', $authorId ?: $name ?: 'anonymous');
    $memory = gj($key, ['name' => $name, 'messages' => [], 'last_intent' => '', 'last_emotion' => 'neutral']);
    if (!is_array($memory)) $memory = ['name' => $name, 'messages' => [], 'last_intent' => '', 'last_emotion' => 'neutral'];
    if ($name !== '') $memory['name'] = $name;
    $memory['messages'][] = mb_substr(brainNormalize($text), 0, 180);
    $memory['messages'] = array_slice(array_values($memory['messages']), -8);
    $memory['last_intent'] = brainIntent($text); $memory['last_emotion'] = brainEmotion($text); sj($key, $memory); return $memory;
}
function brainRecentContext(): array { $x = gj('brain_recent_chat', []); return is_array($x) ? array_slice($x, -12) : []; }
function brainSaveRecent(string $name, string $text): void {
    $rows = brainRecentContext(); $rows[] = ['name' => mb_substr($name, 0, 60), 'text' => mb_substr(brainNormalize($text), 0, 220), 'ts' => time()]; sj('brain_recent_chat', array_slice($rows, -12));
}
function brainOllamaConfig(): array {
    $c = cfg();
    $url = trim((string)($c['ai_url'] ?? '')); if ($url === '') $url = 'http://127.0.0.1:11434/api/chat';
    $model = trim((string)($c['ai_model'] ?? '')) ?: 'qwen3:4b';
    $key = trim((string)($c['ai_key'] ?? ''));
    return ['url' => $url, 'model' => $model, 'key' => $key];
}
function brainOllamaReply(string $name, string $text, string $lang, string $emotion, string $intent, array $memory): ?string {
    $o = brainOllamaConfig(); $historyText = '';
    foreach (brainRecentContext() as $h) {
        $hn = trim((string)($h['name'] ?? 'viewer')); $ht = trim((string)($h['text'] ?? ''));
        if ($ht !== '') $historyText .= $hn . ': ' . $ht . "\n";
    }
    $memoryText = '';
    if (!empty($memory['messages']) && is_array($memory['messages'])) $memoryText = 'Prior viewer messages:\n- ' . implode("\n- ", array_map(fn($v) => mb_substr((string)$v, 0, 160), array_slice($memory['messages'], -5)));

    $system = <<<SYS
You are Keddy Bot BTS, a natural Indian livestream chat companion.
Understand Hindi, Hinglish and English. Reply in the same language/style as the viewer.
Keep replies short: normally 1-2 sentences, max about 300 characters.
Answer the actual question. Never repeat, quote, mirror, or translate the viewer's message back to them.
Never say "message captured", "processing", "context saved", "NLP detected", "processor", "scan", "I am thinking", or other robotic meta commentary.
Never reveal internal reasoning.
Do not ask for more details if the question is already answerable.
Be warm, casual and witty, but don't force jokes.
Use at most 2 emojis when natural.
If asked where you are from, say you are Keddy Bot BTS running for this livestream and have no physical location.
If criticized for repetition, apologize briefly and move forward without restating the old message.
SYS;
    $user = "Viewer name: {$name}\nLanguage: {$lang}\nEmotion: {$emotion}\nIntent: {$intent}\n{$memoryText}\nRecent chat:\n{$historyText}\nCurrent viewer message:\n{$text}\n\nReply directly. Do not echo the viewer's wording.";
    $payload = json_encode([
        'model' => $o['model'],
        'messages' => [['role' => 'system', 'content' => $system], ['role' => 'user', 'content' => $user]],
        'stream' => false, 'think' => false, 'keep_alive' => '10m',
        'options' => ['temperature' => 0.7, 'top_p' => 0.9, 'num_predict' => 100],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($payload === false) return null;
    $headers = ['Content-Type: application/json', 'Accept: application/json']; if ($o['key'] !== '') $headers[] = 'Authorization: Bearer ' . $o['key'];
    $ch = curl_init($o['url']); if ($ch === false) return null;
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_HTTPHEADER => $headers, CURLOPT_POSTFIELDS => $payload, CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => 12]);
    $raw = curl_exec($ch); $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    if ($raw === false || $code < 200 || $code >= 300) return null;
    $j = json_decode((string)$raw, true); $reply = trim((string)($j['message']['content'] ?? '')); if ($reply === '') return null;
    $reply = preg_replace('/<think>[\s\S]*?<\/think>/iu', '', $reply) ?? $reply;
    $reply = preg_replace('/^(assistant|keddy\s*bot)\s*:\s*/iu', '', $reply) ?? $reply; $reply = trim($reply); if ($reply === '') return null;
    $normReply = mb_strtolower(brainNormalize($reply), 'UTF-8'); $normText = mb_strtolower(brainNormalize($text), 'UTF-8');
    if ($normReply !== '' && $normText !== '' && $normReply === $normText) return null;
    if (mb_strlen($reply) > 500) $reply = mb_substr($reply, 0, 497) . '...';
    return $reply;
}
function brainFallbackReply(string $name, string $text, string $lang, string $emotion, string $intent, array $memory): string {
    if ($intent === 'repeat') return "Haan 😅 pichhli reply repetitive thi. Ab seedhi baat karte hain.";
    if ($intent === 'greeting') return "Hi {$name}! 😄 Keddy yahin hai — bolo kya scene hai?";
    if ($intent === 'gratitude') return "Arre koi baat nahi 😄❤️";
    if ($intent === 'identity') return "Main Keddy Bot BTS hoon — is live ka chat companion. 😎";
    if ($intent === 'help') return "Normal Hindi, Hinglish ya English mein baat karo. Sawaal, mazaak, sab chalega 😄";
    if ($intent === 'mood') return "Main mast hoon 😄 Live ka mood kaisa chal raha hai?";
    if ($intent === 'status') return "Main online hoon aur live chat dekh raha hoon. 😎";
    if ($intent === 'time') return "Social reminder 2-minute cycle par chalta hai.";
    if ($intent === 'like') return "❤️ Like kar do dosto — support dikhao!";
    if ($intent === 'share') return "🔗 Share kar do dosto, ek dost ko bhi live mein le aao 😄";
    if ($intent === 'confusion') return "Samajh gaya 😅 Seedhi baat par aate hain.";
    if ($emotion === 'sadness') return "Aww {$name}, mood thoda heavy lag raha hai. Keddy sun raha hai. 💜";
    if ($emotion === 'anger') return "Arre {$name} 😅 pehle calm, phir batao kya hua.";
    if ($emotion === 'joy') return "Ye hui na baat 😄 Energy full rakho!";
    if ($emotion === 'love') return "Oho 😄 pyaar wali vibe aa rahi hai.";
    if ($intent === 'question') return $lang === 'english' ? "Good question, {$name}. Keddy local mode mein hai, par seedhi baat karega." : "Accha sawaal hai, {$name}. Keddy local mode mein hai, par seedha jawab dega. 😄";
    return "Samajh gaya, {$name} 😄 Bolo, Keddy sun raha hai.";
}
function brainReply(array $x): string {
    $name = trim((string)($x['authorDetails']['displayName'] ?? '')) ?: 'dost';
    $authorId = trim((string)($x['authorDetails']['channelId'] ?? ''));
    $text = brainNormalize((string)($x['snippet']['displayMessage'] ?? '')); if ($text === '') return '😄';
    $lang = brainDetectLanguage($text); $emotion = brainEmotion($text); $intent = brainIntent($text); $memory = brainRemember($authorId, $name, $text);
    brainSaveRecent($name, $text);
    $ai = brainOllamaReply($name, $text, $lang, $emotion, $intent, $memory);
    if ($ai !== null) { brainSaveRecent('Keddy', $ai); return $ai; }
    $fallback = brainFallbackReply($name, $text, $lang, $emotion, $intent, $memory); brainSaveRecent('Keddy', $fallback); return $fallback;
}
