<?php
define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', str_replace('\\', '/', __DIR__));
define('INPHINIT_SYSTEM', INPHINIT_ROOT . '/system');

require_once INPHINIT_SYSTEM . '/vendor/inphinit/framework/src/boot.php';

/** @var Inphinit\App $app */

return $app->exec();
