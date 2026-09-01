<?php
declare(strict_types=1);

/*
 * SmartToolz safe database installer.
 * Included by the admin panel. It only adds optional analytics columns
 * when the target table exists and the column is missing.
 * It tolerates older/different analytics schemas and never throws a fatal
 * error just because an expected anchor column is absent.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$db = db();
if (!$db instanceof PDO) {
    return;
}

function stzDbTableExists(PDO $db, string $table): bool
{
    try {
        $q = $db->prepare(
            'SELECT 1 FROM information_schema.tables
             WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1'
        );
        $q->execute([$table]);
        return (bool)$q->fetchColumn();
    } catch (Throwable $e) {
        error_log('SmartToolz DB table check: ' . $e->getMessage());
        return false;
    }
}

function stzDbColumnExists(PDO $db, string $table, string $column): bool
{
    try {
        $q = $db->prepare(
            'SELECT 1 FROM information_schema.columns
             WHERE table_schema = DATABASE()
               AND table_name = ?
               AND column_name = ?
             LIMIT 1'
        );
        $q->execute([$table, $column]);
        return (bool)$q->fetchColumn();
    } catch (Throwable $e) {
        error_log('SmartToolz DB column check: ' . $e->getMessage());
        return false;
    }
}

function stzDbAddColumn(
    PDO $db,
    string $table,
    string $column,
    string $definition
): void {
    if (!stzDbTableExists($db, $table)) {
        return;
    }

    if (stzDbColumnExists($db, $table, $column)) {
        return;
    }

    $allowedColumns = [
        'language',
        'screen',
        'timezone',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    if ($table !== 'analytics_pageviews'
        || !in_array($column, $allowedColumns, true)) {
        return;
    }

    try {
        $anchor = match ($column) {
            'language'     => 'browser',
            'screen'       => 'language',
            'timezone'     => 'screen',
            'utm_source'  => 'timezone',
            'utm_medium'  => 'utm_source',
            'utm_campaign'=> 'utm_medium',
        };

        /*
         * MySQL/MariaDB require the AFTER column to exist. If the current
         * database has a different schema, simply append the new column.
         */
        if (stzDbColumnExists($db, $table, $anchor)) {
            $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition} AFTER `{$anchor}`";
        } else {
            $cleanDefinition = preg_replace(
                '/\s+AFTER\s+`[^`]+`\s*$/i',
                '',
                $definition
            );
            $sql = "ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$cleanDefinition}";
        }

        $db->exec($sql);
    } catch (Throwable $e) {
        /* Never take the admin panel down because an optional migration fails. */
        error_log(
            'SmartToolz DB migration failed for ' .
            $table . '.' . $column . ': ' .
            $e->getMessage()
        );
    }
}

stzDbAddColumn(
    $db,
    'analytics_pageviews',
    'language',
    "VARCHAR(32) NOT NULL DEFAULT '' AFTER `browser`"
);

stzDbAddColumn(
    $db,
    'analytics_pageviews',
    'screen',
    "VARCHAR(32) NOT NULL DEFAULT '' AFTER `language`"
);

stzDbAddColumn(
    $db,
    'analytics_pageviews',
    'timezone',
    "VARCHAR(64) NOT NULL DEFAULT '' AFTER `screen`"
);

stzDbAddColumn(
    $db,
    'analytics_pageviews',
    'utm_source',
    "VARCHAR(255) NOT NULL DEFAULT '' AFTER `timezone`"
);

stzDbAddColumn(
    $db,
    'analytics_pageviews',
    'utm_medium',
    "VARCHAR(255) NOT NULL DEFAULT '' AFTER `utm_source`"
);

stzDbAddColumn(
    $db,
    'analytics_pageviews',
    'utm_campaign',
    "VARCHAR(255) NOT NULL DEFAULT '' AFTER `utm_medium`"
);

return true;
