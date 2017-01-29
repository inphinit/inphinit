<?php
/*
 * Usage with command line: php importpackages.php
 */

define('BOOT_PATH', rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/');

require_once BOOT_PATH . '../vendor/inphinit/framework/src/Inphinit/Packages.php';

$composer = BOOT_PATH . '../vendor/composer/';

$packages = new Inphinit\Packages($composer);

$packages->auto();

$logs = $packages->logs();

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL, PHP_EOL;
}
