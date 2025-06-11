<?php
/*
 * Usage with command line: php importpackages.php
 */

define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', str_replace('\\', '/', dirname(dirname(__DIR__))));
define('INPHINIT_SYSTEM', INPHINIT_ROOT . '/system');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_SYSTEM . '/vendor/inphinit/framework/src/boot.php';

$packages = new Inphinit\Packages();

$packages->setItem('Inphinit\\Experimental\\', 'vendor/inphinit/framework/src/Experimental');
$packages->setItem('Inphinit\\', 'vendor/inphinit/framework/src/Inphinit');
$packages->setItem('Controllers\\', 'Controllers');
$packages->setItem('Models\\', 'Models');

$packages->auto();

$logs = $packages->logs();

// Save mapped classes
$packages->save(INPHINIT_SYSTEM . '/boot/namespaces.php');

// Save autoload file scripts
$packages->saveFiles(INPHINIT_SYSTEM . '/boot/files.php', true);

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL, PHP_EOL;
}
