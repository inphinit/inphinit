<?php
/*
 * Usage with command line: php importpackages.php
 */

define('BOOT_PATH', rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/');

$composerPath = BOOT_PATH . '../vendor/composer/';

if (is_dir($composerPath) === false) {
    echo 'Composer path is not accessible: ', $composerPath;
    exit;
}

$nclassmap = 'autoload_classmap.php';
$npsr0     = 'autoload_namespaces.php';
$npsr4     = 'autoload_psr4.php';

define('PREFIX_PATH', strlen(rtrim(rtrim(realpath(BOOT_PATH . '../'), '/'), '\\')) + 1);

function RemovePrefix($path)
{
    return substr($path, PREFIX_PATH);
}

function AddSlashPackage($prefix)
{
    return str_replace('\\', '\\\\', $prefix);
}

function LoadMap(&$reflibs, $path)
{
    $data = include $path;
    foreach ($data as $key => $value) {
        if (isset($value[0]) && is_string($value[0])) {
            $reflibs[AddSlashPackage($key)] = RemovePrefix($value[0]);
        }
    }
}

$libs = array();

$classmap = $composerPath . $nclassmap;
if (is_file($classmap)) {
    $data = include $classmap;
    foreach ($data as $key => $value) {
        if (false === empty($value)) {
            $libs[AddSlashPackage($key)] = RemovePrefix($value);
        }
    }
}

$psr0 = $composerPath . $npsr0;
if (is_file($psr0)) {
    LoadMap($libs, $psr0);
}

$psr4 = $composerPath . $npsr4;
if (is_file($psr4)) {
    LoadMap($libs, $psr4);
}

$handle = fopen(BOOT_PATH . 'namespaces.php', 'w');
$eol = chr(10);

fwrite($handle, '<?php' . $eol . 'return array(');

$first = true;

foreach ($libs as $key => $value)
{
    fwrite($handle, ($first ? '' : ',') . $eol . "    '" . $key . "' => '" . $value . "'");
    $first = false;
}

fwrite($handle, $eol . ');' . $eol);
