<?php
if (PHP_SAPI !== 'cli-server') {
    header('Content-Type: text/plain', true, 500);
    echo 'server.php is not allowed with "', PHP_SAPI,
            '", use a command like this:', PHP_EOL,
              'php -S localhost:9000 server.php', PHP_EOL;
    exit;
}

$serverPath = strtr(realpath(__DIR__ . '/../../'), '\\', '/');

require_once $serverPath . '/system/vendor/inphinit/framework/src/Utils.php';

$path = UtilsPath();

if (
    $path !== '/' &&
    strcasecmp($path, '/system') !== 0 &&
    stripos($path, '/system/') !== 0 &&
    is_file($serverPath . $path)
) {
    return false;
}

require_once $serverPath . '/index.php';
