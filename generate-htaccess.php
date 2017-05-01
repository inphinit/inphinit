<?php
/*
 * Inphinit
 *
 * Copyright (c) 2017 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 *
 * If using Apache, navigate to: http://[your website]/generate-htaccess.php
 */

require 'system/vendor/inphinit/framework/src/Setup.php';

SetupApache(dirname($_SERVER['PHP_SELF']));
