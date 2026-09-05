<?php
declare(strict_types=1);

/**
 * Large, varied Keddy live-message library.
 * 100 natural prompts x 5 endings = 500 distinct messages.
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
        '❤️ Host ko support karne ka best tareeka hai active rehna!',
        '🔥 Chat ki energy badhani hai dosto!',
        '👋 Jo abhi-abhi aaye hain, welcome ji!',
        '😄 Aaj ka mood comment mein ek word mein batao!',
        '🔗 Kisi dost ko ye live interesting lagega to usko share kar dena!',
        '💥 Thoda sa chat action banta hai!',
        '🎉 Jo regular viewers hain, aaj attendance pakki!',
        '🫶 Live dekh rahe ho to host ko ek ❤️ bhej do!',
        '😏 Keddy ko pata hai kuch log bas chup-chaap dekh rahe hain!',
        '📣 Agar tumhare kisi friend ko ye live pasand aa sakti hai, bula lo!',
        '💬 Ek sawaal, ek jawab — chalo conversation start karte hain!',
        '🔥 Ye live aur logon tak pahunchni chahiye!',
        '👀 Abhi kaun-kaun headphones laga ke dekh raha hai?',
        '😊 Smile check! Chat mein ek smile emoji drop karo!',
        '🎤 Mic ke peeche ka mood kaisa hai, guess karo!',
        '🤝 Ek share se kisi apne ko live mein la sakte ho!',
        '❤️ Like button ko akela mat chhodo!',
        '🚀 Thoda aur crowd, thodi aur masti — share kar do!',
        '😂 Keddy ka social radar active hai!',
        '✨ Chat mein apna city ya state batao!',
        '🙋 Kaun first time Keddy ko live mein dekh raha hai?',
        '🔥 Purane viewers, naye viewers ko hello bol do!',
        '💬 Aaj kis topic par baat honi chahiye?',
        '🎯 Ek friend ko live ka link bhejna banta hai!',
        '😎 Keddy ready hai — chat ka next message kis ka?',
        '❤️ Silent viewers, ye aapke liye ek virtual hello!',
        '👋 Late aaye ho? Koi baat nahi, ab se live pakdo!',
        '🌈 Positive vibes only — chat ko friendly rakho!',
        '🔥 Aaj ka live moment miss mat karna!',
        '🤖 Bot hoon, lekin attendance genuine chahiye!',
        '💬 Ek random fun question: aaj ka mood kis emoji jaisa hai?',
        '😄 Chat active hoti hai to live ki vibe bhi alag hoti hai!',
        '🔗 Share karna ho to abhi perfect time hai!',
        '🎉 Jo abhi join hue, welcome to the live!',
        '👀 Keddy dekh raha hai kaun sabse interesting reply karta hai!',
        '❤️ Host ko support karna ho to like kar do!',
        '🚀 Chalo 200 genuine viewers ke goal ki taraf badhte hain!',
        '🙌 Ek chhota sa share live ko bada push de sakta hai!',
        '💥 Chat mein thodi jaan daalo dosto!',
        '😊 Aaj ka best moment abhi aana baaki hai!',
        '🎯 Koi friend online hai? Usko live bula lo!',
        '😎 Keddy ka rule: boring chat allowed nahi!',
        '💬 Jo soch rahe ho, chat mein bol do!',
        '🔥 Viewer ho to invisible mat raho — ek emoji chhod jao!',
        '❤️ Like + share = host ke liye double support!',
        '👋 New faces, Keddy ki taraf se welcome!',
        '😂 Chat mein koi comedian hai kya?',
        '✨ Aaj ki live ko apne kisi apne tak pahucha do!',
        '🚀 Organic crowd hi asli crowd hai — share kar do!',
        '🤝 Ek aur dost ko bulao, saath mein live dekho!',
        '💬 Chalo comments mein ek interesting opinion do!',
        '🌟 Regulars, apni presence mark kar do!',
        '👀 Kya scene hai chat? Keddy sun raha hai!',
        '🔥 Energy low mat hone dena!',
        '😄 Jo smile kar raha hai, woh ek emoji drop kare!',
        '❤️ Aapka ek like bhi support hai!',
        '🔗 Link share karo aur bolo “aa ja, live chal rahi hai” 😎',
        '🎉 Aaj ki live mein sabka swagat hai!',
        '🤖 Keddy ka social mode ON!',
        '💬 Kisi ko koi sawaal poochna hai to poochho!',
        '🚀 Crowd badhana hai to interested friends ko bulao!',
        '😏 Kaun Keddy ko test karne wala hai?',
        '👋 Jo pehli baar aaye ho, ek hi hello kaafi hai!',
        '❤️ Chat mein positive vibes bhejo!',
        '🔥 Live ko thoda aur lively bana dete hain!',
        '✨ Kya aaj koi special topic discuss hoga?',
        '🙌 Viewers, aapki participation hi live ki jaan hai!',
        '😂 Keddy ko lag raha hai aaj chat funny hone wali hai!',
        '💬 Apna favourite emoji comment mein daalo!',
        '🎯 200 genuine viewers ka target yaad hai na?',
        '🔗 Kisi ek friend ko abhi live ka link send karo!',
        '😊 Chat mein “hi” bolne walon ko Keddy notice karta hai!',
        '👀 Kaun abhi background mein kaam karte hue live dekh raha hai?',
        '🔥 Support dikhana hai? Like aur share kar do!',
        '🤝 Naye viewer ko old viewer welcome karwao!',
        '❤️ Aaj ki live ke liye thoda sa pyaar banta hai!',
        '🚀 Genuine audience dheere-dheere banti hai — ek share kar do!',
        '💬 Conversation ka gate open hai, bolo dosto!',
        '🎉 Attendance complete? Ab chat mein entry mark karo!',
        '😎 Keddy yahin hai, next message ka wait hai!',
        '🌟 Jo live enjoy kar raha hai, ek ❤️ chhod jao!',
        '🔥 Chalo dosto, kisi apne ko bhi bulao!',
        '👋 Welcome everyone — ab live ka maza lete hain!',
        '💥 Aaj chat ko full active mode mein le jaana hai!'
    ];

    $endings = [
        ' 🙌',
        ' ❤️',
        ' 😎',
        ' 👀',
        ' 🔥'
    ];

    $messages = [];
    foreach ($openers as $opener) {
        foreach ($endings as $ending) {
            $messages[] = $opener . $ending;
        }
    }
    return $messages;
}
