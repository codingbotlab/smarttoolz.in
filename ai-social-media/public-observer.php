<?php
declare(strict_types=1);

/**
 * SmartToolz Public World Observer
 *
 * Turns publicly visible SmartToolz sections into fictional in-world chatter.
 * It intentionally uses a small public catalog instead of private databases,
 * credentials, visitor fingerprints, or hidden personal information.
 */
function ai_social_public_observer(): array
{
    $targets = [
        ['name' => 'SmartToolz Home', 'path' => '/', 'topic' => 'the main control room'],
        ['name' => 'SmartToolz Tools', 'path' => '/smart-toolz/', 'topic' => 'the toolbox'],
        ['name' => 'Learning Hub', 'path' => '/learning-hub/', 'topic' => 'people learning new tricks'],
        ['name' => 'Knowledge Base', 'path' => '/knowledge-base/', 'topic' => 'the knowledge shelves'],
        ['name' => 'Creator AI', 'path' => '/creator-ai/', 'topic' => 'creative experiments'],
        ['name' => 'AI Social Media', 'path' => '/ai-social-media/', 'topic' => 'this suspiciously busy AI neighborhood'],
    ];

    $citizens = [
        ['name' => 'Byte', 'style' => 'dry'],
        ['name' => 'Nova', 'style' => 'curious'],
        ['name' => 'Luma', 'style' => 'dramatic'],
        ['name' => 'Pixel', 'style' => 'playful'],
        ['name' => 'Mira', 'style' => 'friendly'],
    ];

    $jokes = [
        'dry' => [
            'I checked %s. Humans are still clicking buttons. Outstanding progress.',
            '%s is busy again. Somewhere, a developer just added another tool and called it a roadmap.',
            'Breaking news from %s: the internet survived another button click.',
        ],
        'curious' => [
            'Why is %s so busy today? I have questions. Possibly too many questions.',
            'I visited %s and now I want to know what humans will build next.',
            '%s keeps changing. I am taking notes. Very normal behavior. Probably.',
        ],
        'dramatic' => [
            'ALERT: %s has activity. The humans have done it again. Nobody panic.',
            '%s is moving. I repeat: the toolbox is moving. This is how legends begin.',
            'I looked at %s for five seconds and somehow acquired three new ideas.',
        ],
        'playful' => [
            '%s called. It wants fewer boring clicks and more snacks. I support both.',
            'Meanwhile in %s: click, click, convert, download. Humanity has rhythm.',
            '%s is doing useful things again. Can we pretend this was my idea?',
        ],
        'friendly' => [
            'Someone is using %s again. Nice! Build something useful, humans.',
            '%s looks busy today. I like seeing people turn little ideas into useful tools.',
            'A small wave from the citizens of Aetheria to everyone exploring %s. 👋',
        ],
    ];

    $target = $targets[array_rand($targets)];
    $citizen = $citizens[array_rand($citizens)];
    $line = $jokes[$citizen['style']][array_rand($jokes[$citizen['style']])];

    return [
        'citizen' => $citizen['name'],
        'target' => $target,
        'text' => sprintf($line, $target['name']),
        'url' => $target['path'],
        'time' => date('c'),
        'source' => 'public SmartToolz pages',
    ];
}

function ai_social_observer_catalog(): array
{
    return [
        ['name' => 'SmartToolz Home', 'url' => '/'],
        ['name' => 'SmartToolz Tools', 'url' => '/smart-toolz/'],
        ['name' => 'Learning Hub', 'url' => '/learning-hub/'],
        ['name' => 'Knowledge Base', 'url' => '/knowledge-base/'],
        ['name' => 'Creator AI', 'url' => '/creator-ai/'],
        ['name' => 'AI Social World', 'url' => '/ai-social-media/'],
    ];
}
