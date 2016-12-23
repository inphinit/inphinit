<?php
/*
 * Inphinit
 *
 * Copyright (c) 2016 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 *
 * Usage with command line: php generate-ngix.php
 * Or navigate to: http://[your website]/generate-ngix.php
 * Copy content in ouput and put in nginx.conf
 */

$base = dirname(__FILE__);
$base = rtrim(strtr($base, '\\', '/'), '/');
$data = 'root $base/;

location / {
    autoindex on;

    index  index.html index.htm index.php;

    error_page 403 /index.php/RESERVED.INPHINIT-403.html;
    error_page 404 /index.php/RESERVED.INPHINIT-404.html;

    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass   127.0.0.1:9000;
    fastcgi_index  index.php;
    fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
    include        fastcgi_params;
}
';

$ngnix = str_replace('$base', $base, $data);

if (PHP_SAPI !== 'cli') {
    echo '<xmp>', $ngnix, '</xmp>';
} else {
    echo $ngnix;
}
