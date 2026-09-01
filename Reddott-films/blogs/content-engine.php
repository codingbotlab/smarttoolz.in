<?php
declare(strict_types=1);

/* Source-grounded long-form article engine. It deliberately separates facts
 * from editorial explanation and never pads an article with invented details.
 */
function rfWords(string $text): array {
    $words=preg_split('/[^a-z0-9]+/i',strtolower($text))?:[];
    $stop=array_flip(['this','that','with','from','your','you','the','and','for','are','was','were','have','has','had','into','about','what','when','where','which','while','will','would','could','should','video','videos','watch','official','reddott','films','film','new','just','more','than','their','they','them','there','here','also','only','using','used','use','how','why','who','like','then','very','some','its','have','been','being','over','under','after','before','through','where','these','those','such']);
    $out=[];foreach($words as $w){if(strlen($w)>=4&&!isset($stop[$w]))$out[]=$w;}
    return array_values(array_unique($out));
}
function rfSubject(string $title,string $description): string {
    $clean=trim(preg_replace('/\s+/',' ',$title)??'');
    $clean=preg_replace('/\s*[|:#–—-]\s*(official|full|hd|4k|video|trailer).*$/i','',$clean)??$clean;
    return trim($clean)?:'this video';
}
function rfTopic(string $title,string $description): array {
    $x=strtolower($title.' '.$description);
    if(preg_match('/\b(animation|animated|anime|cartoon|3d|cgi|vfx|visual)\b/',$x))return ['Animation & Visual Storytelling','animation','visual design, movement, timing and storytelling'];
    if(preg_match('/\b(tutorial|guide|how to|how-to|tips|trick|tool|software|app|coding|php|sql|python|website|seo)\b/',$x))return ['Practical Guide & How-To','tutorial','purpose, workflow, implementation choices and practical learning'];
    if(preg_match('/\b(song|music|lyrics|singer|singing|audio|beat|remix|cover)\b/',$x))return ['Music, Audio & Performance','music','sound, performance, pacing and visual presentation'];
    if(preg_match('/\b(story|short film|movie|cinema|actor|character|drama|horror|comedy|thriller)\b/',$x))return ['Storytelling & Filmmaking','storytelling','story structure, character, pacing and filmmaking choices'];
    return ['Creator Video & Explained','creator video','the central subject, context, presentation and questions raised by the source'];
}
function rfSentences(string $text): array {
    $text=trim(preg_replace('/\s+/',' ',$text)??'');
    $s=preg_split('/(?<=[.!?])\s+/', $text)?:[];
    return array_values(array_filter(array_map('trim',$s),static fn($x)=>strlen($x)>25));
}
function rfFaq(string $subject,string $kind,string $description): array {
    $q1="What is $subject?";
    $base=[
      $q1=>"The title identifies $subject as the main subject of this Reddott Films video. Video-specific details are taken only from the published source and available material.",
      "What should viewers look for in $subject?"=>"Start with the creator's stated purpose, then compare the explanation, demonstration or presentation in the complete video with the published description.",
      "Where can I watch the original $subject video?"=>"The original is available on YouTube. This article is a text companion and keeps a direct link to the source video."
    ];
    if($kind==='tutorial')$base["Who can benefit from this $subject guide?"]="Anyone whose goal matches the stated subject can use the article to understand the concepts before following the practical demonstration in the original video.";
    elseif($kind==='animation')$base["Why does visual storytelling matter for $subject?"]="Movement, framing, timing, composition and visual rhythm can influence how an audience reads a visual work. The video itself should be used to verify its specific creative choices.";
    elseif($kind==='music')$base["What can viewers study in a music-focused video?"]="A viewer can consider sound, performance, arrangement, pacing and the relationship between audio and visuals, without assuming production details that the source does not provide.";
    elseif($kind==='storytelling')$base["What storytelling elements can viewers notice?"]="Where applicable, viewers can examine setup, character motivation, conflict, pacing, information revealed to the audience and resolution.";
    else $base["How should I use this article?"]="Use it as a searchable explanation and viewing guide, then rely on the complete YouTube video for details that text cannot establish.";
    return $base;
}
function rfArticle(string $title,string $description,string $transcript=''): array {
    [$topicLabel,$kind,$angle]=rfTopic($title,$description);
    $subject=rfSubject($title,$description);
    $combined=trim($description."\n".$transcript);
    $keywords=rfWords($title.' '.$combined);
    $sourceSent=rfSentences($combined);
    $descSent=rfSentences($description);
    $txSent=rfSentences($transcript);
    $sourceSummary=$descSent?implode(' ',array_slice($descSent,0,4)):'The published description contains limited detail, so the article avoids unsupported video-specific claims.';
    $variants=["$subject: What the Video Covers and What to Notice","$subject Explained: Key Ideas, Context and Viewing Guide","Understanding $subject: A Practical Reddott Films Companion","$subject: A Closer Look at the Ideas Behind the Video","A Useful Guide to $subject: Context, Questions and Key Ideas"];
    $seed=array_sum(array_map('ord',str_split(substr($title,0,20))));
    $headline=$variants[$seed%count($variants)];
    $intro="Reddott Films presents <strong>".htmlspecialchars($subject,ENT_QUOTES,'UTF-8')."</strong> through this video. This companion article is built around the information actually available from the creator, then adds clearly labelled educational context so readers can understand the subject before, during or after watching. It does not treat assumptions as facts.";
    $sections=[];
    $sections[]=['The subject in context',"$subject is the central subject identified by the published title. The first useful step is to separate that title from everything a reader might assume about the video. The creator's description is therefore used as the primary written source. If it explains a purpose, feature, process or message, the article can organise that information for easier reading. If the source is silent, the article leaves the point open rather than filling the gap with invented details."];
    $sections[]=['What the source actually tells us', $sourceSummary.' This source-first approach matters because a video page can otherwise become a collection of guesses around a short title. A useful article should make clear which information comes from the creator and which material is general explanation. Readers can then return to the video and verify the details for themselves.'];
    $sections[]=["Why $angle matters", "Understanding $subject becomes more useful when the reader knows what to pay attention to. The relevant lens here is $angle. Instead of repeating the same keyword, consider the purpose of the content, the choices used to communicate it, the sequence in which information is presented, and the practical questions a viewer may have. These ideas provide context without claiming that the video contains a specific example unless the source supports it."];
    if($kind==='animation')$sections[]=['Reading animation and visual storytelling',"Animation can communicate through movement, framing, timing, composition, character design and visual rhythm. A viewer can ask what changes from one moment to the next, where attention is directed, and how motion supports the subject. These are general analytical tools, not claims about the production process of this particular video. The finished video remains the correct source for its actual visual choices."];
    elseif($kind==='tutorial')$sections[]=['Turning a practical video into learning',"For a how-to subject, useful learning comes from understanding why an action is performed, not simply copying a sequence. Before following a step, identify its purpose; after completing it, check the result and note anything that depends on the setup shown. This distinction helps readers carry a principle into a different situation while avoiding the mistake of assuming that every possible workflow was demonstrated in the source video."];
    elseif($kind==='music')$sections[]=['Listening and viewing with intention',"Music and performance content can be considered through several layers: the audio, performance, pacing and visual presentation. A useful viewing exercise is to notice how changes in rhythm, emphasis or performance affect attention. The article deliberately avoids assigning technical credits, instruments, recording methods or other production facts unless those details are stated in the source."];
    elseif($kind==='storytelling')$sections[]=['A storytelling lens for the viewer',"Story-focused videos can be approached through setup, motivation, conflict, pacing, revelation and resolution where those elements are present. Even a short work can control audience attention by choosing what information appears first and what is delayed. Rather than inventing a scene-by-scene summary, this article gives readers a framework they can apply to what the complete video actually shows."];
    else $sections[]=['A practical creator-content lens',"Creator videos often communicate through a mixture of explanation, demonstration, narration and presentation. A useful viewer can identify the central promise suggested by the title, then check whether the video answers that promise. Attention to structure and evidence is more useful than repeating keywords, especially when the published description is brief."];
    if($txSent){
        $terms=array_slice(rfWords($transcript),0,10);
        $sections[]=['What the available captions add',"The available caption track gives additional source context around $subject. Recurring terms include ".htmlspecialchars(implode(', ',$terms),ENT_QUOTES,'UTF-8').". Caption text can help identify subjects and phrases used in the video, but it can also miss tone, visuals, timing and other context. It is therefore treated as supporting source material rather than a substitute for watching the complete video."];
    }
    $sections[]=['Questions worth asking while watching',"A strong viewing experience starts with specific questions. What is the central subject? What does the creator spend the most time explaining or showing? Which points are explicitly stated, and which conclusions require interpretation? Does the ending resolve the central purpose suggested by the beginning? These questions help a reader evaluate the content rather than simply consume it."];
    $sections[]=['Key takeaways',"The main takeaway is to connect the searchable topic with the original source. The title identifies the subject, the published description supplies creator-provided context, and available captions can add further language-level evidence. General explanation should help the reader understand the topic, but video-specific claims should always remain traceable to the source. This keeps the article useful without turning it into a rewritten copy of the video description."];
    $sections[]=['Who this companion is for',"This page is intended for readers whose search interest matches $subject and who want useful context before opening YouTube. It can also serve as a recap after watching. The article is not intended to replace the original video; its role is to organise source information, explain relevant concepts and give readers better questions to take back to the source."];
    return ['headline'=>$headline,'topic'=>$topicLabel,'kind'=>$kind,'subject'=>$subject,'intro'=>$intro,'sourceSummary'=>$sourceSummary,'sections'=>$sections,'keywords'=>array_slice($keywords,0,12),'faq'=>rfFaq($subject,$kind,$description),'source_word_count'=>str_word_count($combined),'transcript_word_count'=>str_word_count($transcript)];
}
