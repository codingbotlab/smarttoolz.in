<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/youtube/lib.php';

/**
 * Best-effort access to owner-authorized YouTube captions.
 * Returns plain transcript text when a usable caption track is available.
 * Never fabricates a transcript when captions are unavailable.
 */
function reddottYoutubeTranscript(string $videoId, string $token): string
{
    if (!preg_match('/^[A-Za-z0-9_-]{6,}$/', $videoId)) return '';
    $url='https://www.googleapis.com/youtube/v3/captions?'.http_build_query([
        'part'=>'snippet', 'videoId'=>$videoId
    ],'','&',PHP_QUERY_RFC3986);
    $raw=reddottYoutubeHttpGet($url,$token);
    $data=json_decode($raw,true);
    if (!is_array($data)) return '';
    $tracks=$data['items']??[];
    if (!$tracks) return '';

    usort($tracks,static function(array $a,array $b):int{
        $sa=$a['snippet']??[]; $sb=$b['snippet']??[];
        $score=function(array $s):int{
            $x=0;
            if (($s['language']??'')==='en') $x+=20;
            if (($s['trackKind']??'')==='standard') $x+=5;
            if (($s['status']??'')==='serving') $x+=10;
            return $x;
        };
        return $score($sb)<=>$score($sa);
    });

    foreach ($tracks as $track) {
        $trackId=(string)($track['id']??'');
        if ($trackId==='') continue;
        $download='https://www.googleapis.com/youtube/v3/captions/'.$trackId.'?'.http_build_query(['tfmt'=>'vtt'],'','&',PHP_QUERY_RFC3986);
        try {
            $vtt=reddottYoutubeHttpGet($download,$token);
            $text=reddottVttToText($vtt);
            if (mb_strlen($text)>=120) return mb_substr($text,0,18000);
        } catch (Throwable $e) {
            error_log('Reddott Films captions: '.$e->getMessage());
        }
    }
    return '';
}

function reddottVttToText(string $vtt): string
{
    $vtt=preg_replace('/^WEBVTT.*?\R\R/s','',$vtt)??$vtt;
    $vtt=preg_replace('/<\d{2}:\d{2}:\d{2}\.\d{3}>/','',$vtt)??$vtt;
    $vtt=preg_replace('/<[^>]+>/','',$vtt)??$vtt;
    $lines=preg_split('/\R/',$vtt)?:[];
    $out=[];
    foreach($lines as $line){
        $line=trim($line);
        if($line==='' || preg_match('/^\d{2}:\d{2}:\d{2}[.,]\d{3}\s+-->/',$line) || preg_match('/^\d+$/',$line)) continue;
        $line=preg_replace('/\{[^}]+\}/','',$line)??$line;
        $out[]=$line;
    }
    $text=preg_replace('/\s+/',' ',implode(' ',$out))??'';
    return trim($text);
}
