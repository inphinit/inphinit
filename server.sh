#!/bin/bash

# Setup PHP and PORT
PHP_BIN="/usr/bin/php"
PHP_INI="/etc/php5/cli/php.ini"
HOST_HOST=localhost
HOST_PORT=9000

# Used to restore current dir if using command line
BASEDIR=$(dirname "${0}")

# Router path
ROUTER="$BASEDIR/system/boot/server.php"

# Start built in server
$PHP_BIN -S "$HOST_HOST:$HOST_PORT" -c $PHP_INI -t "$BASEDIR" $ROUTER
