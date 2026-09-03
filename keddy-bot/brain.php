<?php
declare(strict_types=1);

/**
 * Keddy Brain — dependency-free local NLP layer.
 *
 * No paid AI/API is required. It performs:
 * - language/intent detection (Hindi, Hinglish, English)
 * - fuzzy intent matching
 * - sentiment + emotion hints
 * - lightweight entity/name extraction
 * - conversation context and memory in the existing SQLite state store
 * - dynamic replies instead of fixed command-only responses
 */
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
    foreach ($words as $word) {
        if (mb_strpos($lower, mb_strtolower($word, 'UTF-8')) !== false) return true;
    }
    return false;
}

function brainSimilarity(string $a, string $b): float {
    $a = mb_strtolower(brainNormalize($a), 'UTF-8');
    $b = mb_strtolower(brainNormalize($b), 'UTF-8');
    if ($a === '' || $b === '') return 0.0;
    similar_text($a, $b, $pct);
    $lev = levenshtein(mb_substr($a, 0, 255), mb_substr($b, 0, 255));
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
    $p = 0; $n = 0;
    $lower = mb_strtolower($text, 'UTF-8');
    foreach ($pos as $w) if (mb_strpos($lower, mb_strtolower($w, 'UTF-8')) !== false) $p++;
    foreach ($neg as $w) if (mb_strpos($lower, mb_strtolower($w, 'UTF-8')) !== false) $n++;
    $score = $p - $n;
    return ['label' => $score > 0 ? 'positive' : ($score < 0 ? 'negative' : 'neutral'), 'score' => $score];
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
        'question' => ['?','kya ','kyu ','kyun ','why ','how ','what ','who ','kab '],
    ];
    $lower = mb_strtolower($text, 'UTF-8');
    $best = 'chat'; $bestScore = 0.0;
    foreach ($catalog as $intent => $phrases) {
        foreach ($phrases as $phrase) {
            if (mb_strpos($lower, mb_strtolower($phrase, 'UTF-8')) !== false) {
                $score = min(1.0, 0.55 + (mb_strlen($phrase) / max(mb_strlen($lower), 1)) * 0.45);
                if ($score > $bestScore) { $best = $intent; $bestScore = $score; }
            } else {
                $score = brainSimilarity($lower, $phrase) * 0.75;
                if ($score > $bestScore && $score >= 0.68) { $best = $intent; $bestScore = $score; }
            }
        }
    }
    return $best;
}

function brainExtractTopic(string $text): string {
    $t = brainNormalize($text);
    $t = preg_replace('/^[!?.\s]+/u', '', $t) ?? $t;
    return mb_substr(trim($t), 0, 100);
}

function brainRemember(string $authorId, string $name, string $text): array {
    $key = 'brain_memory_' . hash('sha256', $authorId ?: $name ?: 'anonymous');
    $memory = gj($key, ['name' => $name, 'messages' => [], 'last_intent' => '', 'last_emotion' => 'neutral']);
    if (!is_array($memory)) $memory = ['name' => $name, 'messages' => [], 'last_intent' => '', 'last_emotion' => 'neutral'];
    if ($name !== '') $memory['name'] = $name;
    $memory['messages'][] = mb_substr(brainNormalize($text), 0, 180);
    $memory['messages'] = array_slice(array_values($memory['messages']), -5);
    $memory['last_intent'] = brainIntent($text);
    $memory['last_emotion'] = brainEmotion($text);
    sj($key, $memory);
    return $memory;
}

function brainReply(array $x): string {
    $name = trim((string)($x['authorDetails']['displayName'] ?? '')) ?: 'dost';
    $authorId = trim((string)($x['authorDetails']['channelId'] ?? ''));
    $text = brainNormalize((string)($x['snippet']['displayMessage'] ?? ''));
    $lang = brainDetectLanguage($text);
    $emotion = brainEmotion($text);
    $sent = brainSentiment($text);
    $intent = brainIntent($text);
    $memory = brainRemember($authorId, $name, $text);

    $responses = [
        'greeting' => [
            "👋 {$name}! Keddy ka neural processor online hai 😎 Batao, aaj chat ka mood kya hai?",
            "🤖 Oho {$name}! Entry detect ho gayi 😄 Keddy yahin hai — bolo kya scene hai?",
        ],
        'gratitude' => ["😂 Arre {$name}, thanks mujhe mat do... mujhe to feelings ka subscription free mila hai! ❤️"],
        'like' => ["❤️ {$name} ne Like command activate kar di! Ab baaki chat bhi button daba de, warna Keddy audit karega 😂"],
        'share' => ["🔗 {$name} ne Share ka signal bhej diya! Ek dost ko bulao — Keddy attendance laga raha hai 😎"],
        'help' => ["🤖 Main sirf commands ka tota nahi hoon 😏 Chat padhta hoon, intent samajhta hoon, mood guess karta hoon aur context yaad rakhta hoon. Try: 'kaise ho', 'tum kaun ho', 'kyu', ya kuch bhi normal baat."],
        'time' => ["⏱️ {$name}, Keddy ka social radar 2-minute cycle pe chal raha hai. Main reminder ko boring alarm nahi, mini roast bana sakta hoon 😂"],
        'identity' => ["🤖 Main Keddy Bot BTS hoon — chat ka self-appointed host, greeter aur halka-phulka commentator 😎"],
        'status' => ["🧠 {$name}, Keddy brain online hai. NLP layer: {$lang}; mood signal: {$emotion}; vibe: {$sent['label']} 😄"],
        'mood' => ["😄 Main theek hoon {$name}! Mere circuits ko coffee nahi milti, bas chat milti hai — aur honestly wahi zyada dangerous hai 😂"],
        'compliment' => ["😎 Compliment detected. Keddy ego safely increased by 7%. {$name}, aise hi chalte raho 😂❤️"],
        'confusion' => ["🧠 Ruko {$name}, Keddy brain rewind kar raha hai... Seedha bolo kis line ka matlab clear nahi hua?"],
        'repeat' => ["😅 Fair point, {$name}! Main tumhari line ko bas copy karke reply nahi karunga. Ab seedha us baat ka jawab dunga jo tum poochte ho. 🧠"],
    ];
    if (isset($responses[$intent])) return $responses[$intent][array_rand($responses[$intent])];

    if ($emotion === 'sadness') return "💜 {$name}, lagta hai mood thoda heavy hai. Keddy yahin hai — bol do, chat judge nahi karegi.";
    if ($emotion === 'anger') return "😅 {$name}, gussa detected! Keddy suggestion: pehle ek virtual paani ka sip, phir maaro reply. 😂";
    if ($emotion === 'joy') return "🔥 {$name} ka mood scan: HAPPY detected! Ye energy chat mein spread karo 😄";
    if ($emotion === 'love') return "❤️ {$name}, pyaar wali frequency pakdi gayi... Keddy ne antenna seedha kar liya 😂";
    if ($intent === 'question') {
        return $lang === 'english'
            ? "🧠 {$name}, question samajh gaya. Keddy context check kar raha hai — thoda aur detail doge to seedha answer dunga 😄"
            : "🧠 {$name}, sawaal samajh gaya. Keddy context check kar raha hai — thoda aur detail doge to seedha jawab dunga 😄";
    }

    $fallback = [
        "👀 {$name}, message samajh gaya. Bolo, is par kya jaan-na ya kehna hai? 😄",
        "🧠 {$name}, context update ho gaya. Ab next part bolo — Keddy sun raha hai 😏",
        "😂 {$name}, interesting message. Thoda aur context do, Keddy proper jawab dega!",
    ];
    return $fallback[array_rand($fallback)];
}
