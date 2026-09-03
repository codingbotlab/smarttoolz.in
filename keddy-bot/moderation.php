<?php
declare(strict_types=1);

function modNormalize(string $text): string {
    $text = mb_strtolower(trim($text), 'UTF-8');
    $text = preg_replace('/https?:\/\/\S+/iu', ' url ', $text) ?? $text;
    $text = preg_replace('/(.)\1{4,}/u', '$1$1$1', $text) ?? $text;
    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;
    return trim($text);
}
function modContains(string $text, array $words): ?string {
    $t = modNormalize($text);
    foreach ($words as $w) if (mb_strpos($t, mb_strtolower($w, 'UTF-8')) !== false) return $w;
    return null;
}
function modLinks(string $text): int {
    preg_match_all('/(?:https?:\/\/|www\.|t\.me\/|wa\.me\/)/iu', $text, $m);
    return count($m[0] ?? []);
}
function modCaps(string $text): float {
    preg_match_all('/\p{L}/u', $text, $letters); $n = count($letters[0] ?? []);
    if ($n < 10) return 0.0;
    preg_match_all('/\p{Lu}/u', $text, $caps); return count($caps[0] ?? []) / $n;
}
function modRepeated(string $text): bool {
    $words = preg_split('/\s+/u', modNormalize($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if (count($words) < 6) return false;
    foreach (array_count_values($words) as $w => $n) if (mb_strlen($w,'UTF-8') > 2 && $n >= 4) return true;
    return false;
}
function modClassify(string $text): array {
    $slurs = ['nigger','chink','kike','faggot'];
    $threat = ['kill yourself','kys','mar jao','i will kill you','bomb you'];
    $sexual = ['porn','xxx','nude','sex video','sex chat','boobs','blowjob'];
    $scam = ['free iphone','double your money','crypto giveaway','send usdt','investment guarantee','claim prize'];
    $promo = ['sub4sub','subscribe my channel','subscribe to my channel','visit my channel'];
    $profanity = ['motherfucker','bitch','asshole','idiot','chutiya','madarchod','bhosdike','gandu','harami'];
    if (($h=modContains($text,$slurs))!==null) return ['action'=>'ban','severity'=>5,'reason'=>'slur','hit'=>$h];
    if (($h=modContains($text,$threat))!==null) return ['action'=>'ban','severity'=>5,'reason'=>'threat','hit'=>$h];
    if (($h=modContains($text,$sexual))!==null) return ['action'=>'delete','severity'=>4,'reason'=>'sexual','hit'=>$h];
    if (($h=modContains($text,$scam))!==null) return ['action'=>'delete','severity'=>4,'reason'=>'scam','hit'=>$h];
    if (($h=modContains($text,$promo))!==null) return ['action'=>'delete','severity'=>3,'reason'=>'promotion','hit'=>$h];
    if (($h=modContains($text,$profanity))!==null) return ['action'=>'warn','severity'=>2,'reason'=>'profanity','hit'=>$h];
    if (modLinks($text)>=3 || modRepeated($text)) return ['action'=>'delete','severity'=>3,'reason'=>'spam','hit'=>'pattern'];
    if (mb_strlen($text,'UTF-8')>500 || modCaps($text)>0.88) return ['action'=>'warn','severity'=>1,'reason'=>'noise','hit'=>'pattern'];
    return ['action'=>'allow','severity'=>0,'reason'=>'','hit'=>''];
}
function modOffense(string $channelId): int {
    return (int)gv('mod_offense_'.hash('sha256',$channelId),'0');
}
function modRecord(string $channelId): int {
    $n=modOffense($channelId)+1; sv('mod_offense_'.hash('sha256',$channelId),(string)$n); return $n;
}
function modEscalate(string $channelId,array $d): array {
    $n=modRecord($channelId);
    if (($d['severity']??0)>=5 || $n>=3) { $d['action']='ban'; $d['duration']=$n>=4?3600:600; }
    elseif (($d['severity']??0)>=3 || $n>=2) { $d['action']='timeout'; $d['duration']=300; }
    $d['offense']=$n; return $d;
}
function modEligible(array $x): bool {
    $a=$x['authorDetails']??[];
    return !empty($a['channelId']) && empty($a['isChatOwner']) && empty($a['isChatModerator']);
}
function modNotice(string $name,array $d): ?string {
    return match($d['action']??'allow') {
        'warn'=>"⚠️ {$name}, language thodi soft rakho 😄",
        'timeout'=>"🔇 {$name}, 5 minute cooling break. Chat peaceful rakho.",
        'ban'=>"🚫 {$name}, rules break hue — chat se remove kar raha hoon.",
        default=>null,
    };
}
