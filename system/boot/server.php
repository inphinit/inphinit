<?php
$path = $_SERVER['REQUEST_URI'];

if (
    $path !== '/' &&
    strpos($path, '.') !== 0 &&
    strpos($path, '/.') === false &&
    PHP_SAPI === 'cli-server' &&
    is_file($publicPath . $path)
) {
    return false;
}

require_once __DIR__ . '/../../index.php';
