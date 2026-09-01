<?php
declare(strict_types=1);

/*
 * Source-grounded editorial engine.
 * The video title, description and available captions are evidence.
 * General guidance is deliberately framed as context, never as a claim
 * about an unseen scene, person, tool, result or production detail.
 */
function rfClean(string $text): string {
    return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
}
function rfCountWords(string $text): int {
    preg_match_all('/[\p{L}\p{N}]+/u', strip_tags($text), $m);
    return count($m[0] ?? []);
}
function rfWords(string $text): array {
    preg_match_all('/[\p{L}\p{N}]{4,}/u', mb_strtolower($text), $m);
    $stop=array_flip(['this','that','with','from','your','you','the','and','for','are','was','were','have','has','had','into','about','what','when','where','which','while','will','would','could','should','video','videos','watch','official','reddott','films','film','new','just','more','than','their','they','them','there','here','also','only','using','used','use','how','why','who','like','then','very','some','its','been','being','over','under','after','before','through','these','those','such','http','https','www','youtube','com']);
    $out=[];
    foreach(($m[0]??[]) as $w){if(!isset($stop[$w]))$out[]=$w;}
    return array_values(array_unique($out));
}
function rfSubject(string $title,string $description): string {
    $clean=rfClean($title);
    $clean=preg_replace('/\s*[|:#–—-]\s*(official|full|hd|4k|video|trailer|short|reel).*$/iu','',$clean)??$clean;
    return trim($clean)?:'this video';
}
function rfTopic(string $title,string $description): array {
    $x=mb_strtolower($title.' '.$description);
    if(preg_match('/\b(animation|animated|anime|cartoon|3d|cgi|vfx|visual|motion graphics?)\b/u',$x))return ['Animation & Visual Storytelling','animation','visual design, movement, timing and storytelling'];
    if(preg_match('/\b(tutorial|guide|how to|how-to|tips|trick|tool|software|app|coding|php|sql|python|website|seo|generator)\b/u',$x))return ['Practical Guide & How-To','tutorial','purpose, workflow, implementation choices and practical learning'];
    if(preg_match('/\b(song|music|lyrics|singer|singing|audio|beat|remix|cover|performance)\b/u',$x))return ['Music, Audio & Performance','music','sound, performance, pacing and visual presentation'];
    if(preg_match('/\b(story|short film|movie|cinema|actor|character|drama|horror|comedy|thriller|scene)\b/u',$x))return ['Storytelling & Filmmaking','storytelling','story structure, character, pacing and filmmaking choices'];
    return ['Creator Video & Explained','creator video','the central subject, context, presentation and questions raised by the source'];
}
function rfSentences(string $text): array {
    $text=rfClean($text);
    $s=preg_split('/(?<=[.!?।])\s+/u',$text)?:[];
    return array_values(array_filter(array_map('trim',$s),static fn($x)=>rfCountWords($x)>=8));
}
function rfPick(array $items,int $seed): string {
    if(!$items)return '';
    return $items[$seed%count($items)];
}
function rfFaq(string $subject,string $kind,array $sourceSentences): array {
    $e=htmlspecialchars($subject,ENT_QUOTES,'UTF-8');
    $sourceNote=$sourceSentences?'The answer should be checked against the complete source video because the article only has the published text and any available captions.':'The available written source is limited, so the article does not assume details that are not published.';
    $base=[
      "What is $subject?"=>"$subject is the subject identified by the video's published title. $sourceNote",
      "What information is actually confirmed?"=>"Confirmed details are limited to information supplied in the title, description and available caption material. General explanations on this page are context rather than claims about unseen parts of the video.",
      "Who is this article useful for?"=>"It is useful for readers searching for context around $subject, viewers deciding whether to watch, and viewers who want a structured recap of the source's stated topic.",
      "Where can I watch the original?"=>"The complete source is the original YouTube video linked on this page. Watching it is necessary for visual, audio and timing details that text cannot establish."
    ];
    if($kind==='tutorial')$base["How should I learn from this guide?"]="Identify the goal of each step, check the result before moving on, and adapt only what the source supports. If your setup differs, treat the video as a learning reference rather than assuming every environment behaves identically.";
    elseif($kind==='animation')$base["What should I notice in the visual work?"]="Look at movement, framing, timing, composition, visual rhythm and how those choices direct attention. These are useful analysis points, not claims about the production process unless the creator states them.";
    elseif($kind==='music')$base["What can viewers listen for?"]="Consider performance, rhythm, pacing, vocal or instrumental emphasis and the relationship between sound and visuals. Technical credits or recording details should only be accepted when the source provides them.";
    elseif($kind==='storytelling')$base["What storytelling elements are worth examining?"]="Where applicable, notice setup, character motivation, conflict, pacing, information revealed to the audience and resolution. The complete video remains the source for the actual scenes and sequence.";
    return $base;
}
function rfArticle(string $title,string $description,string $transcript=''): array {
    [$topicLabel,$kind,$angle]=rfTopic($title,$description);
    $subject=rfSubject($title,$description);
    $description=rfClean($description);
    $transcript=rfClean($transcript);
    $combined=trim($description." ".$transcript);
    $keywords=rfWords($title.' '.$combined);
    $sourceSent=rfSentences($combined);
    $descSent=rfSentences($description);
    $txSent=rfSentences($transcript);
    $seed=abs(crc32($title));
    $sourceSummary=$descSent?implode(' ',array_slice($descSent,0,4)):'The published description is limited, so this article avoids unsupported video-specific claims.';
    $headlineVariants=[
      "$subject: What the Video Covers and What to Notice",
      "$subject Explained: Key Ideas, Context and Viewing Guide",
      "Understanding $subject: A Practical Reddott Films Companion",
      "$subject: A Closer Look at the Ideas Behind the Video",
      "A Useful Guide to $subject: Context, Questions and Key Ideas"
    ];
    $headline=rfPick($headlineVariants,$seed);
    $intro="This Reddott Films article uses the published information for <strong>".htmlspecialchars($subject,ENT_QUOTES,'UTF-8')."</strong> as its starting point. It organises what the source actually says, adds clearly separated educational context, and gives readers practical questions to use while watching. Where the source does not establish a fact, the article leaves it open instead of guessing.";
    $sections=[];
    $sections[]=['The subject in context',"The title establishes $subject as the main searchable subject. A title can signal a topic, but it cannot by itself prove every detail a reader may associate with that topic. For that reason, this article first works from the creator's published description and then uses available captions as supporting evidence. The goal is to make the source easier to understand without turning assumptions into facts."];
    $sections[]=['What the published source tells us',$sourceSummary." This is the factual starting point for the article. A useful companion page should distinguish between creator-provided information and general explanation, especially when a description is short. Readers can therefore see the topic in context while retaining a clear boundary around what has actually been confirmed by the source."];
    $sections[]=["Why $angle matters","A search result becomes more useful when it answers the questions behind the keyword. For $subject, the reader can consider the purpose of the content, the way information is presented, the sequence of ideas, and what evidence the creator gives for the points being made. These are analytical lenses rather than invented details about the particular video."];
    if($kind==='animation')$sections[]=['Reading animation and visual storytelling',"Animation communicates through choices such as movement, framing, timing, composition, character design and visual rhythm. While watching, ask what changes between moments, where attention is directed and whether motion strengthens the idea being communicated. This framework helps explain why a visual sequence can feel clear, dramatic or engaging without claiming how this specific work was produced."];
    elseif($kind==='tutorial')$sections[]=['Turning a practical video into usable learning',"A practical video is easier to learn from when each action is connected to its purpose. Before following a step, identify what outcome it is intended to produce; after the step, check whether the result matches the explanation. If your setup is different, separate the principle from the exact sequence shown. This prevents a viewer from treating one demonstrated workflow as a universal rule."];
    elseif($kind==='music')$sections[]=['Listening and viewing with intention',"Music and performance content can be understood through several layers: sound, performance, pacing and visual presentation. Notice changes in rhythm, emphasis and energy, then consider how the visuals support or contrast with those changes. The article does not assign instruments, credits, recording methods or technical production facts unless they are explicitly supplied by the source."];
    elseif($kind==='storytelling')$sections[]=['A storytelling lens for the viewer',"Story-focused content can be examined through setup, motivation, conflict, pacing, revelation and resolution when those elements are present. Another useful question is what information is intentionally delayed and when the viewer receives it. Instead of inventing a scene-by-scene summary, this section gives readers a framework they can apply to the complete source."];
    else $sections[]=['A practical creator-content lens',"Creator videos can combine explanation, demonstration, narration and presentation. Start with the central promise suggested by the title, then check whether the video develops that promise with explanation or evidence. Structure matters: knowing what the creator is trying to communicate makes it easier to separate the main idea from secondary details."];
    if($txSent){
        $terms=array_slice(rfWords($transcript),0,10);
        $termText=$terms?implode(', ',$terms):'the subject and wording used in the video';
        $sections[]=['What the available captions add',"The available caption material provides additional language-level context around $subject. Recurring terms include ".htmlspecialchars($termText,ENT_QUOTES,'UTF-8').". Captions can help identify terminology and statements, but they do not fully capture visuals, tone, timing or on-screen information. They are therefore supporting evidence, not a replacement for watching the complete video."];
    }
    $questions=[
      'Questions worth asking while watching',
      'How to get more value from the source',
      'A simple viewing checklist',
      'What to verify in the original video'
    ];
    $sections[]=[rfPick($questions,$seed),"A useful viewing session starts with specific questions. What is the central subject? Which point receives the most attention? Which statements are explicitly supported by the creator? What examples, demonstrations or visual details add meaning? Which conclusions are your own interpretation rather than something the source directly states? Asking these questions turns passive viewing into active understanding."];
    $sections[]=['Key takeaways',"The most useful takeaway is to connect the searchable subject with the original source rather than treating the article as a substitute for it. The title establishes the topic, the description provides creator-published context, and available captions may add wording from the video. General explanation helps readers understand the topic, while source boundaries keep specific claims traceable and honest."];
    $sections[]=['Who should read this companion',"This page is designed for readers whose search interest matches $subject and who want context before opening YouTube. It can also work as a recap after viewing. Its value comes from organising source material, explaining relevant concepts and giving readers better questions—not from repeating the same description or pretending to know details that the source does not provide."];
    $faq=rfFaq($subject,$kind,$sourceSent);
    return [
      'headline'=>$headline,
      'topic'=>$topicLabel,
      'kind'=>$kind,
      'subject'=>$subject,
      'intro'=>$intro,
      'sourceSummary'=>$sourceSummary,
      'sections'=>$sections,
      'keywords'=>array_slice($keywords,0,15),
      'faq'=>$faq,
      'source_word_count'=>rfCountWords($combined),
      'transcript_word_count'=>rfCountWords($transcript),
      'source_sentence_count'=>count($sourceSent),
      'article_word_target'=>'700-1200'
    ];
}
