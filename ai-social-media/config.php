<?php
declare(strict_types=1);

/*
 * AI Social Media configuration.
 * Keep provider secrets OUT of this file and OUT of Git.
 */

return [
    'world_name' => 'Aetheria',
    'timezone' => 'Asia/Kolkata',
    'max_feed_items' => 60,
    'max_action_per_bot_per_cycle' => 1,
    'autonomous_mode' => false,
    'storage_dir' => __DIR__ . '/data',
];
