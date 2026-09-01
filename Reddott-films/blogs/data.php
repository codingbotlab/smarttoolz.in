<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/youtube/lib.php';

function reddottBlogYoutube(string $url, string $token): array {
    $data=json_decode(reddottYoutubeHttpGet($url,$token),true);
    if(!is_array($data)) throw new RuntimeException('Invalid YouTube API response.');
    return $data;
}
function reddottBlogSlug(string $title): string {
    $s=strtolower(trim($title));
    $s=preg_replace('/[^a-z0-9]+/','-',$s) ?? '';
    return trim($s,'-') ?: 'video';
}
function reddottBlogVideos(): array {
    $connection=reddottYoutubeConnection();
    if(!$connection) throw new RuntimeException('YouTube is not connected.');
    $token=reddottYoutubeAccessToken();
    $channel=reddottBlogYoutube('https://www.googleapis.com/youtube/v3/channels?'.http_build_query(['part'=>'contentDetails','id'=>$connection['channel_id']],'','&',PHP_QUERY_RFC3986),$token);
    $uploads=(string)($channel['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ?? '');
    if($uploads==='') return [];
    $all=[];$page='';
    do {
        $params=['part'=>'snippet,contentDetails','playlistId'=>$uploads,'maxResults'=>50];
        if($page!=='') $params['pageToken']=$page;
        $data=reddottBlogYoutube('https://www.googleapis.com/youtube/v3/playlistItems?'.http_build_query($params,'','&',PHP_QUERY_RFC3986),$token);
        foreach(($data['items']??[]) as $item){
            $id=(string)($item['contentDetails']['videoId']??''); if($id==='') continue;
            $s=$item['snippet']??[];
            $all[]=['id'=>$id,'title'=>(string)($s['title']??'Untitled video'),'description'=>(string)($s['description']??''),'published'=>(string)($s['publishedAt']??''),'thumb'=>(string)($s['thumbnails']['high']['url']??$s['thumbnails']['medium']['url']??'')];
        }
        $page=(string)($data['nextPageToken']??'');
    } while($page!=='' && count($all)<500);
    return $all;
}
