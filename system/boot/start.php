<?php
use Inphinit\App;

require_once INPHINIT_PATH . 'vendor/inphinit/framework/src/Utils.php';

if (INPHINIT_COMPOSER) {
    require_once INPHINIT_PATH . 'vendor/autoload.php';
} else {
    UtilsAutoload();
}

UtilsConfig();

if (App::env('development')) {
    require_once INPHINIT_PATH . 'dev.php';
}

require_once INPHINIT_PATH . 'main.php';

App::exec();
