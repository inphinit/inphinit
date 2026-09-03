<?php
/*
 * Usage with command line: ./run pkg:up
 */

$import = new Inphinit\Packages\Import;

// Populates namespace prefixed and classes from Composer
$import->classmap();
$import->psr4();
$import->psr0();

// Populates namespace prefixed and classes from system/boot/namespace.php
$import->boot();

// Manually set namespace prefixed and classes
$import->setItem('Inphinit\\Experimental', 'vendor/inphinit/framework/src/Experimental');
$import->setItem('Inphinit', 'vendor/inphinit/framework/src/Inphinit');
$import->setItem('Commands', 'Commands');
$import->setItem('Controllers', 'Controllers');
$import->setItem('Models', 'Models');

// Populates script files from composer
$import->files();

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
    $pkg->clear();
    $pkg->cache();
} catch (\Exception $ex) {
    echo ' - Warning: ', $ex->getMessage();
}
