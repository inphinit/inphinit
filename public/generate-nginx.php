<?php
/*
 * If using Nginx:
 * - on command line type: php generate-nginx.php
 * - Copy ouput content and put in nginx.conf
 * - Restart Nginx server
 */

require __DIR__ . '/../system/vendor/inphinit/framework/src/setup.php';

setup_nginx(__DIR__ . '/..', '127.0.0.1:9000');
