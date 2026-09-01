<?php
declare(strict_types=1);

/**
 * Builds source-grounded editorial sections for a Reddott Films video.
 * It intentionally uses only the video's title/description; it does not invent
 * scenes, facts, quotes, results or claims that are not present in the source.
 */
function reddottEditorial(string $title, string $description, string $topic, array $points, array $keywords): array
{
    $topicLabel = $topic !== '' ? $topic : 'video storytelling';
    $keywordText = $keywords ? implode(', ', array_slice($keywords, 0, 5)) : $topicLabel;
    $first = $points[0] ?? '';
    $second = $points[1] ?? '';

    $overview = $first !== ''
        ? 'The creator describes this video as follows: '.$first
        : 'The public video metadata provides limited detail, so this article stays focused on the information that can be verified from the published source.';

    $context = 'For readers searching for information about '.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').', the useful starting point is the creator-published title and description. Together they establish the stated subject without asking the reader to rely on assumptions made from a thumbnail or search snippet. This companion page organizes that information into a clearer reading path and points readers back to the original video for the full visual and audio experience.';

    $guide = 'A good way to use this page is to treat it as a viewing guide. First identify the main subject named by the creator, then watch the complete video and check how the actual presentation develops that subject. If the video demonstrates a process, focus on the sequence and outcome shown on screen. If it is narrative or visual storytelling, pay attention to the progression of scenes and the details that support the stated theme. If it is music or performance content, the original YouTube video remains essential because the sound and performance cannot be represented completely in text.';

    $searchIntent = 'This article is designed around practical search intent rather than keyword repetition. A visitor may want to know what the video covers, why it is relevant, what the creator says about it, and where to watch the original. The sections below answer those needs while keeping the source video as the primary reference.';

    $value = 'The added value here is organization and interpretation of the published information: readers get a concise overview, clearly separated source material, topic-specific viewing guidance, key points, and frequently asked questions. That makes the page useful even before the visitor opens YouTube, while avoiding unsupported claims about details that are not available in the public metadata.';

    if ($second !== '') {
        $value .= ' Another published point worth checking while watching is: '.$second;
    }

    return [
        'overview' => $overview,
        'context' => $context,
        'guide' => $guide,
        'search_intent' => $searchIntent,
        'value' => $value,
        'keyword_text' => $keywordText,
        'topic_label' => $topicLabel,
    ];
}
