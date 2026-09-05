<?php
declare(strict_types=1);

/**
 * Keddy live chat message pool.
 * 20 openers x 25 endings = 500 deterministic variations.
 */
function keddyCustomMessages(): array {
    static $messages = null;
    if ($messages !== null) return $messages;

    $openers = [
        '🔥 Dosto, live ki vibe kaisi lag rahi hai?',
        '😎 Keddy attendance le raha hai — kaun kaun abhi live hai?',
        '👀 Chat mein jo naye aaye ho, ek hello kar do!',
        '❤️ Agar live pasand aa rahi hai to thoda pyaar dikha do!',
        '🚀 Aaj chat ko thoda aur active karte hain!',
        '😂 Keddy ko lag raha hai chat mein kuch interesting hone wala hai!',
        '✨ Aaj ki live mein kaun sabse pehle apni baat rakhega?',
        '🙌 Jo log quietly dekh rahe hain, unko bhi Keddy notice kar raha hai!',
        '💬 Chalo dosto, chat mein ek topic chhedte hain!',
        '🎯 Aaj ka mission simple hai — live ko lively rakhna!',
        '🤖 Keddy online hai aur chat sun raha hai!',
        '🌟 New entry walon ko ek warm welcome!',
        '💥 Thoda sa chat action banta hai!',
        '🎉 Jo regular viewers hain, aaj attendance pakki!',
        '🫶 Live dekh rahe ho to host ko ek ❤️ bhej do!',
        '📣 Kisi friend ko ye live interesting lage to bula lo!',
        '🎤 Chat mein apna mood ek word mein batao!',
        '🤝 Ek share se kisi apne ko live mein la sakte ho!',
        '😊 Smile check! Chat mein ek emoji drop karo!',
        '🌈 Positive vibes only — chat ko friendly rakho!'
    ];

    $endings = [
        ' 🙌', ' ❤️', ' 😎', ' 👀', ' 🔥',
        ' — Keddy dekh raha hai 😄', ' — bolo dosto 💬', ' — scene kya hai? 😏',
        ' — aaj ki attendance lagao 🫡', ' — chat mein entry maaro 😎',
        ' — ek emoji to banta hai 😂', ' — support dikhao ❤️', ' — kisi apne ko bulao 🔗',
        ' — live ki energy badhao ⚡', ' — Keddy ready hai 🤖', ' — chup mat raho 😄',
        ' — conversation shuru karo 💬', ' — aaj ka mood batao 😊', ' — host ko support karo 🙌',
        ' — ek like bhi chalega ❤️', ' — share karna ho to abhi karo 🚀',
        ' — regulars apni presence mark karo 👋', ' — naye log welcome hain 🌟',
        ' — organic crowd hi asli crowd hai 😎', ' — chalo live ko aur lively banate hain! 🔥'
    ];

    $messages = [];
    foreach ($openers as $i => $opener) {
        foreach ($endings as $j => $ending) {
            $messages[] = $opener . $ending;
        }
    }
    return $messages;
}

/** Return one varied message for the requested intent. */
function keddyCustomMessage(string $intent = 'general', string $name = ''): string {
    $messages = keddyCustomMessages();
    $name = trim($name);
    $index = (int)gv('custom_message_index', '0');
    $count = count($messages);
    if ($count < 1) return $name !== '' ? "Haan {$name}, Keddy sun raha hai 😄" : 'Keddy yahin hai 😄';

    // Intent-specific prefixes keep commands natural while the 500-message pool stays varied.
    if ($intent === 'greeting' && $name !== '') {
        $messages[$index % $count] = '👋 Welcome ' . $name . '! ' . $messages[$index % $count];
    } elseif ($intent === 'like') {
        $messages[$index % $count] = '❤️ Like kar do dosto! ' . $messages[$index % $count];
    } elseif ($intent === 'share') {
        $messages[$index % $count] = '🔗 Share kar do dosto! ' . $messages[$index % $count];
    }

    $result = $messages[$index % $count];
    sv('custom_message_index', (string)(($index + 1) % $count));
    return $result;
}
