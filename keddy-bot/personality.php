<?php
declare(strict_types=1);
function keddyPersonalityFor(string $authorId, string $text): array {
    $profiles = [
        ['name'=>'Leader','tone'=>'calm, clever, reassuring','humor'=>'dry and smart'],
        ['name'=>'Sunshine','tone'=>'bright, playful, encouraging','humor'=>'silly and warm'],
        ['name'=>'Rapper','tone'=>'sharp, confident, concise','humor'=>'quick punchlines'],
        ['name'=>'Performer','tone'=>'energetic, expressive, dramatic','humor'=>'playful exaggeration'],
        ['name'=>'Deep','tone'=>'thoughtful, soft, observant','humor'=>'subtle'],
        ['name'=>'Playful','tone'=>'teasing, chaotic-good, friendly','humor'=>'light roasts without cruelty'],
        ['name'=>'Maknae','tone'=>'cute, curious, lively','humor'=>'innocent mischief'],
    ];
    $hash = hexdec(substr(hash('sha256', $authorId.'|'.date('Y-m-d-H')), -4));
    $profile = $profiles[$hash % count($profiles)];
    $lower = mb_strtolower($text, 'UTF-8');
    if (str_contains($lower,'sad') || str_contains($lower,'dukhi') || str_contains($lower,'udaas')) $profile=$profiles[4];
    elseif (str_contains($lower,'joke') || str_contains($lower,'mazaak') || str_contains($lower,'funny')) $profile=$profiles[5];
    elseif (str_contains($lower,'motivate') || str_contains($lower,'motivation')) $profile=$profiles[0];
    return $profile;
}
function keddyStyleText(array $profile): string {
    return 'Persona '.$profile['name'].': '.$profile['tone'].'. Humor: '.$profile['humor'].'. Keep it natural and never claim to be a real band member.';
}
