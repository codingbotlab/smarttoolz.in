<?php
declare(strict_types=1);

require_once __DIR__ . '/lib/tools.php';

function smarttoolz_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function smarttoolz_base_url(): string
{
    return 'https://smarttoolz.in';
}
