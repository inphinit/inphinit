<?php
/*
 * If using IIS or IIS Express, navigate to: http://[your website]/generate-webconfig.php
 */

require __DIR__ . '/../system/vendor/inphinit/framework/src/setup.php';

setup_iis(dirname(dirname(dirname($_SERVER['PHP_SELF']))), __DIR__ . '/..');
