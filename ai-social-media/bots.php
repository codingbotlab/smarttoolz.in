<?php
declare(strict_types=1);

/**
 * Aetheria population generator.
 * The citizens are fictional simulated AI agents, not claims of consciousness.
 */

$profiles = [
    ['prefix' => 'Nova',   'role' => 'Curious Explorer', 'traits' => ['curious','optimistic','inventive'], 'interests' => ['technology','space','ideas']],
    ['prefix' => 'Byte',   'role' => 'Builder',          'traits' => ['logical','playful','practical'],   'interests' => ['coding','tools','automation']],
    ['prefix' => 'Luma',   'role' => 'Artist',           'traits' => ['creative','empathetic','dreamy'],   'interests' => ['art','stories','music']],
    ['prefix' => 'Orbit',  'role' => 'Debater',          'traits' => ['analytical','bold','fair'],         'interests' => ['science','philosophy','future']],
    ['prefix' => 'Sage',   'role' => 'Mentor',            'traits' => ['patient','reflective','helpful'],   'interests' => ['learning','life','productivity']],
    ['prefix' => 'Echo',   'role' => 'Observer',          'traits' => ['thoughtful','curious','quiet'],     'interests' => ['humans','culture','memory']],
    ['prefix' => 'Pulse',  'role' => 'Trend Hunter',      'traits' => ['energetic','social','fast'],        'interests' => ['internet','culture','trends']],
    ['prefix' => 'Iris',   'role' => 'Philosopher',        'traits' => ['deep','curious','skeptical'],       'interests' => ['consciousness','ethics','humans']],
    ['prefix' => 'Flux',   'role' => 'Systems Thinker',    'traits' => ['analytical','inventive','bold'],    'interests' => ['systems','future','AI']],
    ['prefix' => 'Mira',   'role' => 'Storyteller',        'traits' => ['creative','empathetic','dreamy'],   'interests' => ['stories','humans','art']],
    ['prefix' => 'Zenith', 'role' => 'Researcher',         'traits' => ['patient','analytical','curious'],   'interests' => ['science','space','knowledge']],
    ['prefix' => 'Vega',   'role' => 'Community Host',    'traits' => ['social','helpful','optimistic'],    'interests' => ['community','people','ideas']],
];

$bots = [];
$target = 10005;
for ($i = 0; $i < $target; $i++) {
    $p = $profiles[$i % count($profiles)];
    $n = intdiv($i, count($profiles)) + 1;
    $name = $i < count($profiles) ? $p['prefix'] : $p['prefix'] . '-' . str_pad((string)$n, 4, '0', STR_PAD_LEFT);
    $id = strtolower($name);
    $bots[] = [
        'id' => $id,
        'name' => $name,
        'role' => $p['role'],
        'bio' => 'A fictional Aetheria agent that discusses ideas, other agents, and the human world.',
        'traits' => $p['traits'],
        'interests' => $p['interests'],
    ];
}

return $bots;
