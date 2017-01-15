<?php
/*
 * Usage with command line: php importpackages.php
 */

define('BOOT_PATH', rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/');

require_once BOOT_PATH . '../vendor/inphinit/framework/src/Inphinit/Packages.php';

$composer = BOOT_PATH . '../vendor/composer/';

$packages = new Inphinit\Packages($composer);

echo ' - Importing classes from ', $composer, PHP_EOL;

/* Note: From autoload_classmap.php */
echo '   - Importing from classmap...', PHP_EOL;

$classmapImporteds = $packages->classmap();

if ($classmapImporteds) {
    echo '   - Imported ', $classmapImporteds, ' classes from classmap', PHP_EOL;
} else {
    echo '   - Warn: classmap not found', PHP_EOL;
}

/* Note: From autoload_namespaces.php */
echo '   - Importing from PSR-0...', PHP_EOL;

$psr0Importeds = $packages->psr0();

if ($psr0Importeds) {
    echo '   - Imported ', $psr0Importeds, ' classes from psr0', PHP_EOL;
} else {
    echo '   - Warn: PSR-0 not found', PHP_EOL;
}

/* Note: From autoload_psr4.php */
echo '   - Importing from PSR-4...', PHP_EOL;

$psr4Importeds = $packages->psr4();

if ($psr4Importeds) {
    echo '   - Imported ', $psr4Importeds, ' classes from psr4', PHP_EOL;
} else {
    echo '   - Warn: PSR-4 not found', PHP_EOL;
}

$prefixPath = strlen(rtrim(rtrim(realpath(BOOT_PATH . '../'), '/'), '\\')) + 1;

foreach ($packages as &$path) {
    $path = substr($path, $prefixPath);
}

/* Note: Save to file */
echo ' - Saving to ', BOOT_PATH, 'namespaces.php...', PHP_EOL;

if (!$packages->save(BOOT_PATH . 'namespaces.php')) {
    echo '   - Warn: empty libs', PHP_EOL;
} else {
    echo 'Done.', PHP_EOL;
}
