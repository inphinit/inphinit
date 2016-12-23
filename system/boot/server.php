<?php
if (PHP_SAPI !== 'cli-server') {
    header('Content-Type: text/plain', true, 500);
    echo 'server.php is not allowed with "', PHP_SAPI,
            '", use a command like this:', PHP_EOL,
              'php -S localhost:9000 server.php', PHP_EOL;
    exit;
}

$serverPath = realpath(dirname(__FILE__) . '/../../');
$serverPath = rtrim(strtr($serverPath, '\\', '/'), '/') . '/';

$path = urldecode(preg_replace('#\?(.*)$#', '', $_SERVER['REQUEST_URI']));
$path = ltrim($path, '/');

if (
    $path !== '' &&
    $path !== '/' &&
    strcasecmp($path, 'system') !== 0 &&
    stripos($path, 'system/') !== 0 &&
    is_file($serverPath . $path)
) {
    return false;
}

require_once $serverPath . 'index.php';
