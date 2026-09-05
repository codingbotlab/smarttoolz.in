<?php
declare(strict_types=1);

// Real-viewer growth assistant: improves discovery and participation without
// creating or simulating viewers, likes, comments, or other engagement.
const KEDDY_VIEWER_GOAL = 200;
const KEDDY_GROWTH_INTERVAL = 180;
const KEDDY_GROWTH_MESSAGES = [
    '🔥 Dosto, live achchi lag rahi ho to Like kar do ❤️ aur Share button se ek friend ko bulao!',
    '👀 New viewers, chat mein ek ❤️ ya Hello drop karo — Keddy yahin hai 😎',
    '🚀 Aaj ka goal: 200 genuine viewers! Kisi ek friend ko live ka link bhej do ❤️',
    '💬 Chat active rakho dosto — sawal poochho, baat karo, aur jo dost interested ho usko live share karo 🙌',
    '🎉 Agar abhi live dekh rahe ho to kisi ek apne ko bulao — real audience milkar 200 ka goal hit karegi!',
    '❤️ Like + Share kar do dosto. Keddy fake viewers nahi banata, asli logon ko bulata hai 🤖',
];

function keddyGrowthViewerCount(string $liveId):?int{
    try{
        $j=yt('liveBroadcasts?part=statistics&id='.rawurlencode($liveId));
        $v=$j['items'][0]['statistics']['concurrentViewers']??null;
        return ($v!==null&&is_numeric($v))?(int)$v:null;
    }catch(Throwable $e){
        sv('growth_viewer_error',$e->getMessage());
        sv('growth_viewer_error_at',(string)time());
        return null;
    }
}

function keddyGrowthMessage(?int $viewers):string{
    if($viewers!==null && $viewers>=KEDDY_VIEWER_GOAL){
        return '🎉 200+ genuine viewers! ❤️ Thank you dosto — Like, Share aur chat mein connected raho!';
    }
    if($viewers!==null){
        $remaining=max(0,KEDDY_VIEWER_GOAL-$viewers);
        if($viewers<25)return '🚀 Abhi '.$viewers.' viewers live hain! '.$remaining.' aur genuine viewers ka goal hai — ek friend ko live ka link bhejo ❤️';
        if($viewers<100)return '🔥 '.$viewers.' viewers! 200 ke liye '.$remaining.' aur chahiye — Share karke 1-2 interested friends ko bulao 🙌';
        return '💥 '.$viewers.' viewers! Bas '.$remaining.' aur genuine viewers — chat active rakho aur live share karo ❤️';
    }
    return KEDDY_GROWTH_MESSAGES[array_rand(KEDDY_GROWTH_MESSAGES)];
}

function keddyGrowthShareUrl(string $liveId):string{
    return 'https://www.youtube.com/watch?v='.rawurlencode($liveId);
}

function keddyGrowthTick(array $live):bool{
    if(empty($live['id'])||empty($live['chat']))return false;
    $liveId=(string)$live['id'];
    $lastLive=(string)gv('growth_live_id','');
    if($lastLive!==$liveId){
        sv('growth_live_id',$liveId);
        sv('growth_last_sent','0');
        sv('growth_viewer_count','');
    }
    $viewers=keddyGrowthViewerCount($liveId);
    sv('growth_viewer_count',$viewers===null?'':(string)$viewers);
    $last=(int)gv('growth_last_sent','0');
    if(time()-$last<KEDDY_GROWTH_INTERVAL)return false;
    $msg=keddyGrowthMessage($viewers).' 🔗 '.keddyGrowthShareUrl($liveId);
    try{
        sendBotMsg($msg);
        sv('growth_last_send_error','');
        sv('growth_last_sent',(string)time());
        sv('growth_last_send_ok',(string)time());
        return true;
    }catch(Throwable $e){
        sv('growth_last_send_error',$e->getMessage());
        sv('growth_last_send_error_at',(string)time());
        return false;
    }
}

function keddyGrowthStatus():array{
    $viewers=gv('growth_viewer_count');
    $last=(int)gv('growth_last_sent','0');
    return [
        'goal'=>KEDDY_VIEWER_GOAL,
        'viewers'=>$viewers!==null&&$viewers!==''?(int)$viewers:null,
        'remaining'=>$viewers!==null&&$viewers!==''?max(0,KEDDY_VIEWER_GOAL-(int)$viewers):null,
        'last_sent_at'=>$last?:null,
        'last_error'=>gv('growth_last_send_error','')?:null,
    ];
}
