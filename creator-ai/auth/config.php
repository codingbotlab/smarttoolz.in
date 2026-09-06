<?php
/**
 * SmartToolz legacy database compatibility shim.
 *
 * The old database-backed ad/account system has been retired. This shim
 * provides the tiny query surface used by un-migrated legacy tool pages so
 * they can fail closed without a database, tracking, accounts or ad storage.
 */
declare(strict_types=1);

if (!class_exists('SmartToolzLegacyEmptyStatement')) {
    class SmartToolzLegacyEmptyStatement {
        public function fetch(?int $mode = null) { return false; }
        public function fetchAll(?int $mode = null): array { return []; }
    }
}

if (!class_exists('SmartToolzLegacyEmptyDb')) {
    class SmartToolzLegacyEmptyDb {
        public function query(string $sql): SmartToolzLegacyEmptyStatement {
            return new SmartToolzLegacyEmptyStatement();
        }
        public function prepare(string $sql): SmartToolzLegacyEmptyStatement {
            return new SmartToolzLegacyEmptyStatement();
        }
    }
}

if (!function_exists('db')) {
    function db(): SmartToolzLegacyEmptyDb {
        static $db;
        if (!$db) {
            $db = new SmartToolzLegacyEmptyDb();
        }
        return $db;
    }
}
