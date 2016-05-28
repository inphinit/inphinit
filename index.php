<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

use Inphinit\App;

define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', rtrim(strtr(dirname(__FILE__), '\\', '/'), '/') . '/');
define('INPHINIT_PATH', INPHINIT_ROOT . 'system/');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_PATH . 'boot/start.php';

if (App::env('developer') === true) {
    require_once INPHINIT_PATH . 'dev.php';
}

require_once INPHINIT_PATH . 'main.php';

App::exec();
