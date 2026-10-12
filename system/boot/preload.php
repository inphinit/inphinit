<?php
// This script should only be used with opcache.preload=

use Inphinit\Experimental\Utility\OPcache;

define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', str_replace('\\', '/', dirname(dirname(__DIR__))));
define('INPHINIT_SYSTEM', INPHINIT_ROOT . '/system');

require_once INPHINIT_SYSTEM . '/vendor/inphinit/framework/src/boot.php';

OPcache::preload(array(
    // Main
    Inphinit\App::class,
    Inphinit\Config::class,
    Inphinit\Event::class,
    Inphinit\Session::class,
    Inphinit\Viewing\View::class,

    // HTTP
    Inphinit\Http\Request::class,
    Inphinit\Http\Resource::class,
    Inphinit\Http\Response::class,
    Inphinit\Http\Treaty::class,

    // Utility
    Inphinit\Utility\Arrays::class,
    Inphinit\Utility\PropertyAccessor::class,
    Inphinit\Utility\Strings::class,

    // Experimental
    Inphinit\Experimental\Environment\Env::class,
    Inphinit\Experimental\Http\Cache::class,
    Inphinit\Experimental\Http\CookieJar::class,
));
