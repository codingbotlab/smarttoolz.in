<?php
declare(strict_types=1);

/*
 * SmartToolz one-time/safe database installer.
 * Included from admin/index.php so missing analytics columns are created
 * automatically on the live database. Every migration checks the schema
 * before altering anything, making repeated requests safe.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$db = db();
if (!$db instanceof PDO) {
    throw new RuntimeException('Database connection unavailable.');
}

function stzDbTableExists(PDO $db, string $table): bool
{
    $q = $db->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1');
    $q->execute([$table]);
    return (bool)$q->fetchColumn();
}

function stzDbColumnExists(PDO $db, string $table, string $column): bool
{
    $q = $db->prepare('SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1');
    $q->execute([$table, $column]);
    return (bool)$q->fetchColumn();
}

function stzDbAddColumn(PDO $db, string $table, string $column, string $definition): void
{
    if (!stzDbTableExists($db, $table) || stzDbColumnExists($db, $table, $column)) {
        return;
    }

    $allowedTables = ['analytics_pageviews'];
    $allowedColumns = ['language','screen','timezone','utm_source','utm_medium','utm_campaign'];

    if (!in_array($table, $allowedTables, true) || !in_array($column, $allowedColumns, true)) {
        throw new RuntimeException('Unsafe migration target.');
    }

    $db->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
}

/* Analytics pageview dimensions used by the advanced admin analytics panel. */
stzDbAddColumn($db, 'analytics_pageviews', 'language', "VARCHAR(32) NOT NULL DEFAULT '' AFTER `browser`");
stzDbAddColumn($db, 'analytics_pageviews', 'screen', "VARCHAR(32) NOT NULL DEFAULT '' AFTER `language`");
stzDbAddColumn($db, 'analytics_pageviews', 'timezone', "VARCHAR(64) NOT NULL DEFAULT '' AFTER `screen`");
stzDbAddColumn($db, 'analytics_pageviews', 'utm_source', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `timezone`");
stzDbAddColumn($db, 'analytics_pageviews', 'utm_medium', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `utm_source`");
stzDbAddColumn($db, 'analytics_pageviews', 'utm_campaign', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `utm_medium`");

return true;
