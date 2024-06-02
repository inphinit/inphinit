<?php
/*
 * Usage with command line: php importpackages.php
 */

require_once '../../index.php';

$packages = new Inphinit\Packages(INPHINIT_SYSTEM . '/vendor/composer');

$packages->auto();

$logs = $packages->logs();

$packages->save(INPHINIT_SYSTEM . '/boot/[namespaces].php');

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL, PHP_EOL;
}
