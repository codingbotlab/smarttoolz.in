<?php
declare(strict_types=1);

function smarttoolz_apply_tool_categories(array &$tools): void
{
    foreach ($tools as &$tool) {
        if (!isset($tool['category']) || trim((string)$tool['category']) === '') {
            $tool['category'] = 'Other';
        }
    }
    unset($tool);
}
