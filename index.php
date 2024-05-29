<?php
define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', strtr(__DIR__, '\\', '/') . '/');
define('INPHINIT_PATH', INPHINIT_ROOT . 'system/');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_PATH . 'vendor/inphinit/framework/src/boot.php';

Inphinit\App::exec();
