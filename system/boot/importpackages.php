<?php
/*
 * Usage with command line: php importpackages.php
 */

define('BOOT_PATH', strtr(__DIR__, '\\', '/') . '/');
define('PACKAGES_PATH', BOOT_PATH . '../vendor/inphinit/framework/src/Inphinit/Packages.php');
define('INPHINIT_PATH', dirname(BOOT_PATH) . '/');

if (false === is_file(PACKAGES_PATH)) {
    exit;
}

require_once PACKAGES_PATH;

$composer = BOOT_PATH . '../vendor/composer/';

$packages = new Inphinit\Packages($composer);

$packages->auto();

$logs = $packages->logs();

$packages->save(BOOT_PATH . 'namespaces.php');

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL, PHP_EOL;
}
