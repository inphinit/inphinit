<?php
/*
 * If using Apache, navigate to: http://[your website]/generate-htaccess.php
 */

require '../vendor/inphinit/framework/src/Setup.php';

SetupApache(dirname(dirname(dirname($_SERVER['PHP_SELF']))), __DIR__ . '/../..');
