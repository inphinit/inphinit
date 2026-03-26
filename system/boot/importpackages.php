<?php
/*
 * Usage with command line: ./run pkg:up
 */

$import = new Inphinit\Packages\Import;

$import->setItem('Inphinit\\Experimental\\', 'vendor/inphinit/framework/src/Experimental');
$import->setItem('Inphinit\\', 'vendor/inphinit/framework/src/Inphinit');
$import->setItem('Commands\\', 'Commands');
$import->setItem('Controllers\\', 'Controllers');
$import->setItem('Models\\', 'Models');

$import->auto();

$logs = $import->logs();

// Save mapped classes
$import->save(INPHINIT_SYSTEM . '/boot/namespaces.php');

// Save autoload file scripts
$import->saveFiles(INPHINIT_SYSTEM . '/boot/files.php', true);

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL;
}

try {
    $pkg = new Inphinit\Packages\Package;
    $pkg->cache();
} catch (\Exception $ee) {
    echo ' - Warning: ', $ee->getMessage();
}
