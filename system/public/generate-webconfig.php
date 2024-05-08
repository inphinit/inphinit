<?php
/*
 * If using IIS or IIS Express, navigate to: http://[your website]/generate-webconfig.php
 */

require '../vendor/inphinit/framework/src/Setup.php';

SetupIIS(dirname(dirname(dirname($_SERVER['PHP_SELF']))), __DIR__ . '/../..');
