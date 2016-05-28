<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 */

require_once INPHINIT_PATH . 'vendor/inphinit/framework/src/Utils.php';

if (INPHINIT_COMPOSER) {
    require_once INPHINIT_PATH . 'vendor/autoload.php';
} else {
    UtilsAutoload();
}

UtilsConfig();
