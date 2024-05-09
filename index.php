<?php
define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', strtr(__DIR__, '\\', '/') . '/');
define('INPHINIT_PATH', INPHINIT_ROOT . 'system/');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_PATH . 'boot/start.php';
