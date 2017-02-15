<?php
/*
 * Inphinit
 *
 * Copyright (c) 2017 Guilherme Nascimento (brcontainer@yahoo.com.br)
 *
 * Released under the MIT license
 *
 * Navigate to: http://[your website]/generate-htaccess.php
 */

if (
    empty($_SERVER['SERVER_SOFTWARE']) ||
    stripos($_SERVER['SERVER_SOFTWARE'], 'apache') === false
) {
    echo 'Use this script only with Apache';
    exit;
}

$base = dirname($_SERVER['PHP_SELF']);
$base = rtrim(strtr($base, '\\', '/'), '/');

$data = '<IfModule mod_negotiation.c>
    Options -MultiViews
</IfModule>

IndexIgnore *

# Redirect page errors to route system
ErrorDocument 401 ' . $base . '/index.php/RESERVED.INPHINIT-401.html
ErrorDocument 403 ' . $base . '/index.php/RESERVED.INPHINIT-403.html
ErrorDocument 501 ' . $base . '/index.php/RESERVED.INPHINIT-501.html

RewriteEngine On

# Disable access to folder and redirect ./system/* path to "routes"
RewriteRule ^system/ index.php [L]

# Check file or folders exists
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f

# Redirect all urls to index.php if no exits files/folder
RewriteRule ^ index.php [L]
';

file_put_contents('.htaccess', $data);

echo '<pre>', htmlspecialchars($data), '</pre>';
