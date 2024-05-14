<?php
/*
 * If using Apache, navigate to: http://[your website]/generate-htaccess.php
 */

require __DIR__ . '/../system/vendor/inphinit/framework/src/setup.php';

setup_apache(dirname(dirname($_SERVER['PHP_SELF'])), __DIR__ . '/..');
