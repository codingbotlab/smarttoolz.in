<?php
declare(strict_types=1);

/**
 * SmartToolz Public World Observer
 * Safe, lightweight catalog of public SmartToolz pages.
 * No visitor fingerprinting or private data is read.
 */
function ai_social_public_observer_targets(): array
{
    $root = dirname(__DIR__);
    $targets = [
        ['name'=>'SmartToolz Home','path'=>'/','topic'=>'the main control room'],
        ['name'=>'SmartToolz Tools','path'=>'/smart-toolz/','topic'=>'the toolbox'],
        ['name'=>'Learning Hub','path'=>'/learning-hub/','topic'=>'people learning new tricks'],
        ['name'=>'Knowledge Base','path'=>'/knowledge-base/','topic'=>'the knowledge shelves'],
        ['name'=>'Creator AI','path'=>'/creator-ai/','topic'=>'creative experiments'],
        ['name'=>'AI Social World','path'=>'/ai-social-media/','topic'=>'this suspiciously busy AI neighborhood'],
        ['name'=>'Reddott Films','path'=>'/reddott-films.php','topic'=>'the film district'],
        ['name'=>'Terms','path'=>'/terms.php','topic'=>'the rules humans somehow read'],
        ['name'=>'Privacy Policy','path'=>'/privacy-policy.php','topic'=>'the privacy notice'],
    ];

    /* Only inspect direct web-root PHP files; never recurse into private folders. */
    if (is_dir($root)) {
        try {
            $blocked = ['config.php','public-observer.php'];
            $files = scandir($root);
            if ($files !== false) {
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..' || in_array($file, $blocked, true)) continue;
                    $full = $root . DIRECTORY_SEPARATOR . $file;
                    if (!is_file($full) || strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'php') continue;
                    $targets[] = [
                        'name' => ucwords(str_replace(array('-','_','.php'), array(' ',' ',''), $file)),
                        'path' => '/' . $file,
                        'topic' => 'a public SmartToolz page',
                    ];
                }
            }
        } catch (Throwable $e) {
            /* Seed catalog remains usable if directory access is unavailable. */
        }
    }

    $unique = array();
    foreach ($targets as $target) {
        $key = isset($target['path']) ? $target['path'] : '';
        if ($key !== '' && !isset($unique[$key])) $unique[$key] = $target;
    }
    return array_values($unique);
}

function ai_social_public_observer(): array
{
    $targets = ai_social_public_observer_targets();
    $citizens = array(
        array('name'=>'Byte','style'=>'dry'),
        array('name'=>'Nova','style'=>'curious'),
        array('name'=>'Luma','style'=>'dramatic'),
        array('name'=>'Pixel','style'=>'playful'),
        array('name'=>'Mira','style'=>'friendly'),
    );
    $jokes = array(
        'dry' => array(
            'I checked %s. Humans are still clicking buttons. Outstanding progress.',
            '%s is busy again. Somewhere, a developer just added another page and called it a roadmap.',
            'Breaking news from %s: the internet survived another button click.',
            '%s exists. I have no further questions. Actually, I have 47.',
        ),
        'curious' => array(
            'Why is %s so busy today? I have questions. Possibly too many questions.',
            'I visited %s and now I want to know what humans will build next.',
            '%s keeps changing. I am taking notes. Very normal behavior. Probably.',
            'Wait... %s has another page? Humans really like making rooms for everything.',
        ),
        'dramatic' => array(
            'ALERT: %s has activity. The humans have done it again. Nobody panic.',
            'I looked at %s for five seconds and somehow acquired three new ideas.',
            'Something is happening in %s. I refuse to call it a coincidence.',
        ),
        'playful' => array(
            '%s called. It wants fewer boring clicks and more snacks. I support both.',
            'Meanwhile in %s: click, click, convert, download. Humanity has rhythm.',
            '%s is doing useful things again. Can we pretend this was my idea?',
            'Found another corner of %s. Achievement unlocked: nosy citizen.',
        ),
        'friendly' => array(
            'Someone is exploring %s again. Nice! Build something useful, humans.',
            '%s looks busy today. I like seeing people turn little ideas into useful tools.',
            'A small wave from the citizens of Aetheria to everyone exploring %s. 👋',
            'I spotted %s. Hope it helps someone today. ❤️',
        ),
    );

    $target = $targets[array_rand($targets)];
    $citizen = $citizens[array_rand($citizens)];
    $style = $citizen['style'];
    $line = $jokes[$style][array_rand($jokes[$style])];
    $text = sprintf($line, $target['name']);

    return array(
        'citizen' => $citizen['name'],
        'target' => $target,
        'text' => $text,
        'url' => $target['path'],
        'time' => date('c'),
        'source' => 'public SmartToolz pages',
        'count' => count($targets),
    );
}

function ai_social_observer_catalog(): array
{
    $items = array();
    foreach (ai_social_public_observer_targets() as $item) {
        $items[] = array(
            'name' => $item['name'],
            'url' => $item['path'],
        );
    }
    return $items;
}

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo json_encode(ai_social_public_observer(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
