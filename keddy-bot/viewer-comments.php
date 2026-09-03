<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';

function commentDbInit():void{
    db()->exec("CREATE TABLE IF NOT EXISTS viewer_comment_history (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        viewer_channel_id TEXT NOT NULL,
        viewer_name TEXT NOT NULL,
        video_id TEXT NOT NULL,
        video_title TEXT NOT NULL,
        video_url TEXT NOT NULL,
        comment_text TEXT NOT NULL,
        status TEXT NOT NULL DEFAULT 'posted',
        error_text TEXT NOT NULL DEFAULT '',
        created_at INTEGER NOT NULL
    )");
    db()->exec('CREATE INDEX IF NOT EXISTS idx_viewer_comment_created ON viewer_comment_history(created_at DESC)');
    db()->exec('CREATE INDEX IF NOT EXISTS idx_viewer_comment_user_video ON viewer_comment_history(viewer_channel_id,video_id)');
}
commentDbInit();

function commentHistory(int $limit=50):array{
    $q=db()->prepare('SELECT * FROM viewer_comment_history ORDER BY created_at DESC,id DESC LIMIT ?');
    $q->bindValue(1,max(1,min($limit,200)),PDO::PARAM_INT);$q->execute();
    return $q->fetchAll(PDO::FETCH_ASSOC)?:[];
}
function commentStats():array{
    $today=strtotime('today');
    $q=db()->prepare("SELECT COUNT(*) FROM viewer_comment_history WHERE created_at>=? AND status='posted'");$q->execute([$today]);
    $posted=(int)$q->fetchColumn();
    $q=db()->query("SELECT COUNT(*) FROM viewer_comment_history WHERE status='posted'");$total=(int)$q->fetchColumn();
    $q=db()->query("SELECT COUNT(*) FROM viewer_comment_history WHERE status='failed'");$failed=(int)$q->fetchColumn();
    $q=db()->query("SELECT created_at FROM viewer_comment_history WHERE status='posted' ORDER BY created_at DESC LIMIT 1");$last=$q?$q->fetchColumn():false;
    return ['today'=>$posted,'total'=>$total,'failed'=>$failed,'last'=>$last?((int)$last):0];
}
function commentWasPosted(string $viewerId,string $videoId):bool{
    $q=db()->prepare("SELECT 1 FROM viewer_comment_history WHERE viewer_channel_id=? AND video_id=? AND status='posted' LIMIT 1");
    $q->execute([$viewerId,$videoId]);return(bool)$q->fetchColumn();
}
function logViewerComment(string $viewerId,string $viewerName,string $videoId,string $title,string $url,string $text,string $status='posted',string $error=''):void{
    $q=db()->prepare('INSERT INTO viewer_comment_history(viewer_channel_id,viewer_name,video_id,video_title,video_url,comment_text,status,error_text,created_at) VALUES(?,?,?,?,?,?,?,?,?)');
    $q->execute([$viewerId,$viewerName,$videoId,$title,$url,$text,$status,$error,time()]);
}
function composeViewerComment(string $title,string $description=''):string{
    $s=mb_strtolower($title.' '.$description,'UTF-8');
    if(preg_match('/dance|cover|dancecover|choreo|kpop|performance/ui',$s))return 'Okay, this energy is seriously good 🔥 The performance feels so alive — loved the vibe!';
    if(preg_match('/travel|trip|vlog|korea|seoul|tour|journey/ui',$s))return 'This looks like such a fun journey 😄 Loved the atmosphere and the little details in this vlog!';
    if(preg_match('/food|recipe|cooking|cook|bake|baking|street food/ui',$s))return 'Now you have officially made me hungry 😂 This looks so good — the whole video has a great vibe!';
    if(preg_match('/gaming|gameplay|minecraft|valorant|pubg|free fire|fortnite/ui',$s))return 'That was a fun watch 🎮 The video has great energy and the gameplay moments were seriously entertaining!';
    if(preg_match('/music|song|singing|cover song|vocal|guitar|piano/ui',$s))return 'This has such a nice vibe 🎶 Loved the feel of the video — keep making more like this!';
    if(preg_match('/art|drawing|sketch|painting|craft|design/ui',$s))return 'The creativity here is so good 👏 Really enjoyed seeing the idea come together!';
    return 'This was genuinely a nice watch 😄 Loved the vibe and the effort you put into it. Keep going!';
}
function runViewerVideoCommentTick():array{
    $last=(int)gv('viewer_comments_last_run','0');
    if(time()-$last<3600)return ['ran'=>false,'reason'=>'cooldown'];
    sv('viewer_comments_last_run',(string)time());
    try{
        $ch=connectedChannel();
        if(!$ch)throw new RuntimeException('Streamer YouTube channel is not connected.');
        $j=yt('commentThreads?part=snippet&allThreadsRelatedToChannelId='.rawurlencode($ch['id']).'&maxResults=20&order=time&textFormat=plainText');
        $picked=0;$seen=[];
        foreach($j['items']??[] as $thread){
            $top=$thread['snippet']['topLevelComment']['snippet']??[];
            $viewerId=(string)($top['authorChannelId']['value']??'');
            $viewerName=(string)($top['authorDisplayName']??'viewer');
            if($viewerId===''||$viewerId===$ch['id']||isset($seen[$viewerId]))continue;
            $seen[$viewerId]=1;
            $vc=yt('channels?part=contentDetails,snippet&id='.rawurlencode($viewerId));
            $vch=$vc['items'][0]??null;if(!$vch)continue;
            $uploads=(string)($vch['contentDetails']['relatedPlaylists']['uploads']??'');if($uploads==='')continue;
            $pi=yt('playlistItems?part=snippet&playlistId='.rawurlencode($uploads).'&maxResults=1');
            $video=$pi['items'][0]['snippet']??null;if(!$video)continue;
            $videoId=(string)($video['resourceId']['videoId']??'');$title=(string)($video['title']??'');if($videoId===''||$title===''||commentWasPosted($viewerId,$videoId))continue;
            $info=yt('videos?part=snippet&id='.rawurlencode($videoId));$sn=$info['items'][0]['snippet']??$video;
            $comment=composeViewerComment($title,(string)($sn['description']??''));
            $url='https://www.youtube.com/watch?v='.rawurlencode($videoId);
            try{
                $posted=yt('commentThreads?part=snippet','POST',['snippet'=>['channelId'=>$video['channelId']??$viewerId,'videoId'=>$videoId,'topLevelComment'=>['snippet'=>['textOriginal'=>$comment]]]]);
                if(!empty($posted['id'])){logViewerComment($viewerId,$viewerName,$videoId,$title,$url,$comment,'posted');$picked++;break;}
                logViewerComment($viewerId,$viewerName,$videoId,$title,$url,$comment,'failed','YouTube did not return a comment id.');
            }catch(Throwable $e){logViewerComment($viewerId,$viewerName,$videoId,$title,$url,$comment,'failed',$e->getMessage());}
        }
        return ['ran'=>true,'posted'=>$picked];
    }catch(Throwable $e){
        sv('viewer_comments_last_error',$e->getMessage());
        return ['ran'=>true,'posted'=>0,'error'=>$e->getMessage()];
    }
}

$notice='';$error='';
if(isset($_GET['run'])&&$_GET['run']==='1'){
    $r=runViewerVideoCommentTick();
    if(!empty($r['error']))$error=$r['error'];else $notice=!empty($r['posted'])?'✅ Comment posted and added to history.':(!empty($r['ran'])?'ℹ️ Hourly check completed. No eligible new video found.':'⏳ Hourly cooldown is still active.');
}
$stats=commentStats();$rows=commentHistory(100);$next=max(0,3600-(time()-(int)gv('viewer_comments_last_run','0')));
function vh($x):string{return htmlspecialchars((string)$x,ENT_QUOTES,'UTF-8');}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Keddy • Viewer Comments</title>
<style>*{box-sizing:border-box}body{margin:0;background:#090b10;color:#f4f7fb;font:14px/1.5 system-ui,-apple-system,Segoe UI,sans-serif}main{max-width:1250px;margin:auto;padding:24px}.top{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}.brand{font-size:26px;font-weight:850}.brand span{color:#a78bfa}.muted{color:#94a0b4}.btn{display:inline-block;border:0;border-radius:11px;padding:11px 15px;background:#7c3aed;color:#fff;text-decoration:none;font-weight:750}.btn.secondary{background:#1b2230}.notice,.error{padding:12px 14px;border-radius:12px;margin-bottom:16px}.notice{background:#103a2b}.error{background:#481c24}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px}.card{background:#111620;border:1px solid #252c39;border-radius:18px;padding:18px}.num{font-size:26px;font-weight:850;margin-top:3px}.tableWrap{overflow:auto}.table{width:100%;border-collapse:collapse;min-width:900px}.table th,.table td{padding:13px 10px;border-bottom:1px solid #242a36;text-align:left;vertical-align:top}.table th{color:#aeb7c7;font-size:12px;text-transform:uppercase;letter-spacing:.05em}.user{font-weight:750}.comment{max-width:430px}.video a{color:#c4b5fd;text-decoration:none}.status{display:inline-flex;padding:4px 8px;border-radius:99px;font-size:12px;font-weight:700;background:#123a2b;color:#86efac}.status.failed{background:#491d26;color:#fda4af}.hero{display:flex;justify-content:space-between;align-items:center;gap:18px;margin-bottom:16px}.hero h1{margin:0 0 5px;font-size:30px}.empty{text-align:center;padding:45px 10px;color:#8994a7}.small{font-size:12px;color:#7f8a9e}@media(max-width:800px){main{padding:15px}.stats{grid-template-columns:repeat(2,1fr)}.hero{align-items:flex-start;flex-direction:column}.hero h1{font-size:25px}}</style></head>
<body><main>
<div class="top"><div class="brand">🤖 Keddy <span>Viewer Comments</span></div><a class="btn secondary" href="index.php">← Dashboard</a></div>
<?php if($notice):?><div class="notice"><?=vh($notice)?></div><?php endif;?>
<?php if($error):?><div class="error">⚠️ <?=vh($error)?></div><?php endif;?>
<div class="card hero"><div><h1>Comment History</h1><div class="muted">Front-end log: kis viewer ki kis video par kya comment hua aur kab.</div></div><div><a class="btn" href="?run=1">▶ Run hourly check now</a></div></div>
<div class="stats"><div class="card"><div class="muted">Today</div><div class="num"><?=$stats['today']?></div></div><div class="card"><div class="muted">All posted</div><div class="num"><?=$stats['total']?></div></div><div class="card"><div class="muted">Failed</div><div class="num"><?=$stats['failed']?></div></div><div class="card"><div class="muted">Next check</div><div class="num" id="countdown">--:--</div></div></div>
<div class="card"><div class="tableWrap"><table class="table"><thead><tr><th>Viewer</th><th>Video</th><th>What Keddy commented</th><th>When</th><th>Status</th></tr></thead><tbody>
<?php if(!$rows):?><tr><td colspan="5" class="empty">Abhi koi comment history nahi hai. Pehla successful comment yahan dikhega.</td></tr>
<?php else: foreach($rows as $r):?><tr><td><div class="user"><?=vh($r['viewer_name'])?></div><div class="small"><?=vh($r['viewer_channel_id'])?></div></td><td class="video"><a href="<?=vh($r['video_url'])?>" target="_blank" rel="noopener"><?=vh($r['video_title'])?></a></td><td class="comment"><?=vh($r['comment_text'])?></td><td><?=date('d M Y, h:i A',(int)$r['created_at'])?></td><td><span class="status <?=$r['status']==='failed'?'failed':''?>"><?=vh(strtoupper($r['status']))?></span><?php if($r['error_text']):?><div class="small"><?=vh($r['error_text'])?></div><?php endif;?></td></tr><?php endforeach; endif;?>
</tbody></table></div></div>
<div class="small" style="margin-top:14px">Hourly protection: same viewer + same video gets only one posted comment in history. The runner currently stops after one successful comment per hourly cycle.</div>
</main><script>let left=<?=json_encode($next)?>;const e=document.getElementById('countdown');function tick(){left=Math.max(0,left);e.textContent=String(Math.floor(left/60)).padStart(2,'0')+':'+String(left%60).padStart(2,'0');left--}tick();setInterval(tick,1000);</script></body></html>
