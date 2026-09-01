<?php
declare(strict_types=1);

/*
 * Topic-aware editorial layer. It uses the actual YouTube title/description as
 * source material, then adds clearly generic topic education. It never invents
 * video-specific facts. Every article receives a different combination of
 * headings, search intent, explanations and FAQs.
 */
function rfWords(string $text): array {
    $words=preg_split('/[^a-z0-9]+/i',strtolower($text))?:[];
    $stop=array_flip(['this','that','with','from','your','you','the','and','for','are','was','were','have','has','had','into','about','what','when','where','which','while','will','would','could','should','video','videos','watch','official','reddott','films','film','new','just','more','than','their','they','them','there','here','also','only','using','used','use','how','why','who']);
    $out=[];foreach($words as $w){if(strlen($w)>=4&&!isset($stop[$w]))$out[]=$w;}
    return array_values(array_unique($out));
}
function rfSubject(string $title,string $description): string {
    $clean=trim(preg_replace('/\s+/',' ',$title)??'');
    $clean=preg_replace('/\s*[|:#–—-]\s*(official|full|hd|4k|video).*$/i','',$clean)??$clean;
    return trim($clean)?:'this video';
}
function rfTopic(string $title,string $description): array {
    $x=strtolower($title.' '.$description);
    if(preg_match('/\b(animation|animated|anime|cartoon|3d|cgi|vfx|visual)\b/',$x))return ['Animation & Visual Storytelling','animation','how animation, visual design and storytelling work together'];
    if(preg_match('/\b(tutorial|guide|how to|how-to|tips|trick|tool|software|app|coding|php|sql|python|website|seo)\b/',$x))return ['Practical Guide & How-To','tutorial','the concepts, workflow and practical decisions behind the subject'];
    if(preg_match('/\b(song|music|lyrics|singer|singing|audio|beat|remix|cover)\b/',$x))return ['Music, Audio & Performance','music','the creative and technical ideas listeners can notice in the subject'];
    if(preg_match('/\b(story|short film|movie|cinema|actor|character|drama|horror|comedy|thriller)\b/',$x))return ['Storytelling & Filmmaking','storytelling','story structure, character, pacing and filmmaking choices relevant to the subject'];
    return ['Creator Video & Explained','creator video','the main subject, context and viewing questions suggested by the published information'];
}
function rfFaq(string $subject,string $kind,string $description): array {
    $base=[
      "What is $subject?"=>"The title identifies $subject as the main subject of this Reddott Films video. The article does not add a video-specific claim that is absent from the published source.",
      "What should viewers look for in $subject?"=>"Start with the details stated in the creator's description, then compare them with what is actually presented in the complete video.",
      "Where can I watch the original $subject video?"=>"The original is available on the Reddott Films YouTube channel; this page provides the direct YouTube link as well as the embedded player."
    ];
    if($kind==='tutorial')$base["Who can benefit from this $subject guide?"]="Anyone trying to understand the stated workflow can use the article as a preparation or recap, while the original video provides the practical demonstration.";
    elseif($kind==='animation')$base["Why does visual storytelling matter for $subject?"]="Visual storytelling can communicate mood, sequence and emphasis through composition, movement and timing. The specific choices in this video should be verified by watching it.";
    elseif($kind==='music')$base["What makes a music video useful to study?"]="A music video can be considered through audio, performance, pacing and visual presentation. This article avoids assigning specific production details unless the source states them.";
    elseif($kind==='storytelling')$base["What storytelling elements can viewers notice?"]="Viewers can pay attention to setup, progression, character motivation, pacing and resolution where those elements are present in the video.";
    else $base["How should I use this article?"]="Use it as a text companion and viewing guide, then rely on the complete YouTube video for details that cannot be represented accurately in text.";
    return $base;
}
function rfArticle(string $title,string $description): array {
    [$topicLabel,$kind,$angle]=rfTopic($title,$description);$subject=rfSubject($title,$description);$words=rfWords($title.' '.$description);$keywords=array_slice($words,0,8);$sent=preg_split('/(?<=[.!?])\s+/',trim($description))?:[];$sent=array_values(array_filter(array_map('trim',$sent)));
    $sourceSummary=$sent?implode(' ',array_slice($sent,0,min(3,count($sent)))):'The published YouTube description contains limited detail, so this article deliberately avoids adding unsupported video-specific claims.';
    $headlineVariants=["$subject: What the Video Covers and What to Notice","$subject Explained: Key Ideas, Context and Viewing Guide","Understanding $subject: A Practical Reddott Films Companion","$subject: A Closer Look at the Ideas Behind the Video"];
    $seed=array_sum(array_map('ord',str_split(substr($title,0,12))));$headline=$headlineVariants[$seed%count($headlineVariants)];
    $intro="Reddott Films presents <strong>".htmlspecialchars($subject,ENT_QUOTES,'UTF-8')."</strong> through this video. This companion article is designed for people who want more than an embedded player: it explains the topic in plain language, identifies useful questions to consider while watching, and separates source information from general background.";
    $sections=[];
    $sections[]=['Understanding the subject',"At its simplest, $subject is the starting point for this article. The useful question is not only what the title says, but what a viewer can understand from the material surrounding that title. The creator's published description is therefore treated as the primary textual source. Where it provides a concrete point, that point is retained; where it is silent, this page does not pretend to know a video-specific fact. This approach keeps the article useful without turning assumptions into claims."];
    $sections[]=['Why this topic matters',"The broader value of $subject comes from understanding the ideas behind it rather than memorising a title. For $angle, readers can focus on purpose, process, presentation and the choices that affect the final result. These are useful lenses for both beginners and experienced viewers because they turn passive watching into active observation. The exact examples should come from the video itself."];
    $sections[]=['How to read the video critically',"A good companion article should help a reader ask better questions. Begin with the stated subject, identify the central action or message, and notice which details receive the most attention. Then compare those observations with the published description. If a detail is not supported by either source, it should remain an open question rather than becoming a fabricated takeaway. This is especially important when a short title can be interpreted in several ways."];
    if($kind==='animation')$sections[]=['Animation and visual storytelling lens',"Animation communicates through more than dialogue. Movement, framing, timing, colour, character design, transitions and visual rhythm can all influence how an audience understands a scene. When watching this Reddott Films piece, notice which visual elements carry information and which are mainly decorative. The goal is not to assume how the production was made, but to observe what the finished work actually communicates."];
    elseif($kind==='tutorial')$sections[]=['Practical learning lens',"For a tutorial-style subject, the strongest way to learn is to connect each demonstrated step with its purpose. A viewer can pause after an important action, reproduce it independently, and check the result before moving on. It is also useful to distinguish a general principle from a step that only applies to the specific setup shown in the video. This makes the article useful as a learning companion without claiming that an unshown workflow is part of the video."];
    elseif($kind==='music')$sections[]=['Music and performance lens',"Music-focused videos can be appreciated through several layers: the song or audio itself, performance, arrangement, pacing and visual presentation. While watching, notice how sound and image support each other and whether changes in rhythm or performance affect the viewer's attention. This article does not assign technical production credits or musical details unless they are explicitly available in the source information."];
    elseif($kind==='storytelling')$sections[]=['Storytelling and filmmaking lens',"For a story or film-focused subject, viewers can examine setup, character motivation, conflict, pacing and payoff. Even a short piece can create a sense of progression by deciding what information to reveal and when. Rather than summarising scenes that are not documented in the source, this page provides a framework for analysing what the viewer actually sees and hears."];
    else $sections[]=['Creator-content lens',"Creator videos often combine a central idea with presentation choices such as pacing, visuals, narration or demonstrations. A useful way to watch is to identify the central promise suggested by the title, then check how the finished video fulfils that promise. The published description provides the safest textual reference; the video supplies the details that text alone cannot capture."];
    $sections[]=['Key takeaways',"The strongest takeaway is to connect the published source with what is actually shown. Keep the creator's stated information separate from general topic knowledge, verify important details by watching the complete video, and use this page as a searchable reference rather than a replacement for the original. That gives readers a clearer path from search intent to the source material."];
    $sections[]=['Who should watch this video?',"This page is suitable for readers whose search interest matches $subject and who want a quick explanation before opening YouTube. It can also work as a recap for someone who has already watched the video. The usefulness comes from organising the topic and providing questions to consider, while the original video remains the authority for video-specific details."];
    return ['headline'=>$headline,'topic'=>$topicLabel,'kind'=>$kind,'subject'=>$subject,'intro'=>$intro,'sourceSummary'=>$sourceSummary,'sections'=>$sections,'keywords'=>$keywords,'faq'=>rfFaq($subject,$kind,$description)];
}
