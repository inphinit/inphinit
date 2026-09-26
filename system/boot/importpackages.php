<?php
/*
 * Usage with command line: ./run pkg:up
 */

use Inphinit\Packages\Import;
use Inphinit\Packages\Package;

// Import namespaces and classes path to the inphinit-autoloader system
$import = new Import;

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

// Create a script containing an array that maps all populated classes and namespaces
$import->save(INPHINIT_SYSTEM . '/boot/namespaces.php');

// Creates a script to load scripts mapped from `"autoload": {"files": [...]}`
$import->saveFiles(INPHINIT_SYSTEM . '/boot/files.php', true);

echo 'Importing packages:', PHP_EOL;

if (count($logs) > 0) {
    echo PHP_EOL, ' - ', implode(PHP_EOL . ' - ', $logs), PHP_EOL;
}

try {
    // Create cache metadata of composer packages
    $pkg = new Package(INPHINIT_ROOT . '/composer.lock');

    // Clear metadata cache
    $pkg->clear();

    // Create metadata cache
    $pkg->cache();

    echo ' - Updated package metadata', PHP_EOL;
} catch (\Exception $ex) {
    echo ' - Warning: ', $ex->getMessage(), PHP_EOL;
}
