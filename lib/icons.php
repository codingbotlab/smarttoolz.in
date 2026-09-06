<?php
declare(strict_types=1);

/** Render a SmartToolz icon from the local SVG sprite. */
function st_icon(string $name, string $class = 'st-icon', string $label = ''): string
{
    $safe = preg_replace('/[^a-z0-9_-]/i', '', $name) ?: 'build';
    $safeClass = preg_replace('/[^a-z0-9_-]/i', ' ', $class) ?: 'st-icon';
    $aria = $label === '' ? ' aria-hidden="true"' : ' role="img" aria-label="'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').'"';
    return '<svg class="'.htmlspecialchars($safeClass, ENT_QUOTES, 'UTF-8').'" viewBox="0 0 24 24" focusable="false"'.$aria.'><use href="/assets/icons/smarttoolz-icons.svg#'.htmlspecialchars($safe, ENT_QUOTES, 'UTF-8').'"/></svg>';
}
