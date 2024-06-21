<?php
/*
 * Usage with command line: php importpackages.php
 */

define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', str_replace('\\', '/', realpath('../..')));
define('INPHINIT_SYSTEM', INPHINIT_ROOT . '/system');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_SYSTEM . '/vendor/inphinit/framework/src/Inphinit/Packages.php';

$packages = new Inphinit\Packages(INPHINIT_SYSTEM . '/vendor/composer');

$packages->setItem('Inphinit\\Experimental\\', 'vendor/inphinit/framework/src/Experimental');
$packages->setItem('Inphinit\\', 'vendor/inphinit/framework/src/Inphinit');

$packages->auto();

$logs = $packages->logs();

$packages->save(INPHINIT_SYSTEM . '/boot/namespaces.php');

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL, PHP_EOL;
}
