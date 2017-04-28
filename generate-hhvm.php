<?php
/*
 * Inphinit
 *
 * Copyright (c) 2017 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 *
 * Note: This is used only fast-cgi (eg. nginx)
 *
 * Usage with command line: php generate-hhvm.php
 * Or navigate to: http://[your website]/generate-hhvm.php
 * Copy content in ouput and put in nginx.conf
 */

$base = dirname(__FILE__);
$base = rtrim(strtr($base, '\\', '/'), '/');
$data = 'root "' . $base . '/";

# Disable access to folder and redirect ./system/* path to "routes"
location ~ ^/(system/|system$) {
    rewrite ^ /index.php last;
}

location / {
    autoindex on;

    index  index.html index.htm index.php;

    error_page 403 /index.php/RESERVED.INPHINIT-403.html;
    error_page 404 /index.php/RESERVED.INPHINIT-404.html;

    try_files $uri $uri/ /index.php?$query_string;
}

# Option, your server may have already been configured
location ~ \.(php|hh)$ {
    fastcgi_pass   127.0.0.1:9000; # Replace by your fastcgi
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    include        fastcgi_params;
}
';

if (PHP_SAPI !== 'cli') {
    echo '<pre>', htmlspecialchars($data), '</pre>';
} else {
    echo $data;
}
