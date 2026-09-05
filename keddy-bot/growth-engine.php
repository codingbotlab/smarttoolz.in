<?php
declare(strict_types=1);

const KEDDY_VIEWER_GOAL = 200;
const KEDDY_GROWTH_INTERVAL = 300;
const KEDDY_GROWTH_MESSAGES = [
    '🔥 Dosto, live pasand aa rahi ho to Like kar do aur is live ko ek dost ke saath Share kar do ❤️',
    '👀 Abhi jo log live mein ho, chat mein apna naam/hello drop karo! Aur ek friend ko bulao 😎',
    '🚀 Keddy ka goal hai aaj ki live ko aur logon tak pahunchana — Share button dabao aur kisi apne ko bulao ❤️',
    '💬 Chat mein active raho dosto! Aapka comment aur genuine engagement live ko aur discoverable banane mein help kar sakta hai 🙌',
    '❤️ Agar stream achchi lag rahi hai to Like + Share kar do. 200 viewers ka goal hai — real logon ko bulao, bot nahi! 🤖',
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
        return '🎉 200+ viewers! ❤️ Thank you dosto — Like karo, Share karo aur chat mein connected raho!';
    }
    if($viewers!==null){
        $remaining=max(0,KEDDY_VIEWER_GOAL-$viewers);
        return '🚀 '.$viewers.' viewers abhi live mein hain! '.$remaining.' aur genuine viewers ka goal hai — ek friend ko Share karke bulao ❤️';
    }
    return KEDDY_GROWTH_MESSAGES[array_rand(KEDDY_GROWTH_MESSAGES)];
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
    $last=(int)gv('growth_last_sent','0');
    if(time()-$last<KEDDY_GROWTH_INTERVAL)return false;
    $viewers=keddyGrowthViewerCount($liveId);
    sv('growth_viewer_count',$viewers===null?'':(string)$viewers);
    sv('growth_last_sent',(string)time());
    try{
        sendBotMsg(keddyGrowthMessage($viewers));
        sv('growth_last_send_error','');
        sv('growth_last_sent_ok',(string)time());
        return true;
    }catch(Throwable $e){
        sv('growth_last_send_error',$e->getMessage());
        sv('growth_last_send_error_at',(string)time());
        return false;
    }
}
