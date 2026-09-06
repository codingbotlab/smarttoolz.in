<?php
declare(strict_types=1);

// SmartToolz is fully database-free. Shared helpers live here.
if (!function_exists('smarttoolz_h')) {
    function smarttoolz_h(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
