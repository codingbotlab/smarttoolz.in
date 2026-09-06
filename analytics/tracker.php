<?php
/**
 * SmartToolz legacy analytics compatibility shim.
 *
 * Analytics/tracking has been retired. This file intentionally performs
 * no tracking, stores no visitor data and emits no output. It remains only
 * so older tool files that still require the former path do not fatal-error
 * while the remaining pages are migrated to the database-free architecture.
 */
declare(strict_types=1);
