<?php
/*
 * If using Nginx:
 * - on command line type: php generate-nginx.php
 * - Or navigate to: http://[your website]/generate-nginx.php
 * - Copy content in ouput and put in nginx.conf
 * - Restart Nginx server
 */

require 'system/vendor/inphinit/framework/src/Setup.php';

//Customize extensions used by PHP
$extensions = array( 'php' );

//If using HHVM uncomment this line:
/* $extensions = array( 'php', 'hh' ); */

SetupNginx(__DIR__, $extensions);
