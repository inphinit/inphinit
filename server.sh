#!/bin/bash
PHP_BIN="/usr/bin/php"
PHP_INI="/etc/php5/cli/php.ini"
HOST_PORT=9000
$PHP_BIN -S localhost:$HOST_PORT -c $PHP_INI "system/boot/server.php"
