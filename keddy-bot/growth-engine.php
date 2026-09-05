<?php
declare(strict_types=1);
require_once __DIR__.'/custom-messages.php';

// Real-viewer growth assistant. It only encourages genuine audience discovery;
// it never creates or simulates viewers, likes, comments, or other engagement.
const KEDDY_VIEWER_GOAL = 200;
// Sending a live-chat message costs quota, so keep automated prompts conservative.
const KEDDY_GROWTH_INTERVAL = 600;

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
        if($viewers<25)return '🚀 Abhi '.$viewers.' viewers live hain! '.$remaining.' aur genuine viewers ka goal hai — ek friend ko live ka link bhej do ❤️';
        if($viewers<100)return '🔥 '.$viewers.' viewers! 200 ke liye '.$remaining.' aur chahiye — Share karke 1-2 interested friends ko bulao 🙌';
        return '💥 '.$viewers.' viewers! Bas '.$remaining.' aur genuine viewers — chat active rakho aur live share karo ❤️';
    }

    // 500-message local library: varied wording without repeated spam templates.
    $pool=keddyCustomMessages();
    if(!$pool)return '❤️ Live dekh rahe ho to ek friend ko share kar do!';
    $index=(int)gv('growth_message_index','0');
    $msg=$pool[$index%count($pool)];
    sv('growth_message_index',(string)(($index+1)%count($pool)));
    return $msg;
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
        sv('growth_message_index','0');
    }
    // IMPORTANT: do not call the viewer-statistics endpoint on every heartbeat.
    // Check the send interval first; this removes unnecessary API calls.
    $last=(int)gv('growth_last_sent','0');
    if(time()-$last<KEDDY_GROWTH_INTERVAL)return false;
    $viewers=keddyGrowthViewerCount($liveId);
    sv('growth_viewer_count',$viewers===null?'':(string)$viewers);
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
