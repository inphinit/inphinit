<?php
if (PHP_SAPI !== 'cli-server') {
    header('Content-Type: text/plain', true, 500);
    echo 'server.php is not allowed with ', PHP_SAPI, ', use a command like this: ./server';
    exit;
}

$serverPath = strtr(realpath(__DIR__ . '/../../'), '\\', '/');

require_once $serverPath . '/system/vendor/inphinit/framework/src/Utils.php';

if (UtilsFileFromBuiltIn($serverPath . '/system/public')) {
    return false;
}

require_once $serverPath . '/index.php';
