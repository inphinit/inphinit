<?php
/*
 * If using IIS or IIS Express, navigate to: http://[your website]/generate-webconfig.php
 */

require 'system/vendor/inphinit/framework/src/Setup.php';

SetupIIS(dirname($_SERVER['PHP_SELF']));
