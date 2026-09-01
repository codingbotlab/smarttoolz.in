-- SmartToolz Analytics schema fix
-- Safe to run once in phpMyAdmin / MySQL.
-- Adds language + advanced client dimensions only when missing.

SET @db = DATABASE();

SET @sql = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
          AND column_name = 'language'
    ),
    'ALTER TABLE `analytics_pageviews` ADD COLUMN `language` VARCHAR(32) NOT NULL DEFAULT '''' AFTER `browser`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
          AND column_name = 'screen'
    ),
    'ALTER TABLE `analytics_pageviews` ADD COLUMN `screen` VARCHAR(32) NOT NULL DEFAULT '''' AFTER `language`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
          AND column_name = 'timezone'
    ),
    'ALTER TABLE `analytics_pageviews` ADD COLUMN `timezone` VARCHAR(64) NOT NULL DEFAULT '''' AFTER `screen`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
          AND column_name = 'utm_source'
    ),
    'ALTER TABLE `analytics_pageviews` ADD COLUMN `utm_source` VARCHAR(255) NOT NULL DEFAULT '''' AFTER `timezone`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
          AND column_name = 'utm_medium'
    ),
    'ALTER TABLE `analytics_pageviews` ADD COLUMN `utm_medium` VARCHAR(255) NOT NULL DEFAULT '''' AFTER `utm_source`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = @db
          AND table_name = 'analytics_pageviews'
          AND column_name = 'utm_campaign'
    ),
    'ALTER TABLE `analytics_pageviews` ADD COLUMN `utm_campaign` VARCHAR(255) NOT NULL DEFAULT '''' AFTER `utm_medium`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verify the final schema.
SELECT column_name, column_type, is_nullable, column_default
FROM information_schema.columns
WHERE table_schema = @db
  AND table_name = 'analytics_pageviews'
  AND column_name IN ('language','screen','timezone','utm_source','utm_medium','utm_campaign')
ORDER BY ordinal_position;
