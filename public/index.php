<?php
// register_shutdown_function(function () {
//     echo round(memory_get_peak_usage(true) / 1024 / 1024, 5), 'MB';
// });

define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', str_replace('\\', '/', dirname(__DIR__)));
define('INPHINIT_SYSTEM', INPHINIT_ROOT . '/system');

require_once INPHINIT_SYSTEM . '/vendor/inphinit/framework/src/boot.php';

/** @var Inphinit\App $app */

$app->exec();
