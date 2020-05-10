#!/bin/bash

# Setup PHP and PORT
PHP_BIN="/usr/bin/php"
PHP_INI="/etc/php5/cli/php.ini"
HOST_HOST=localhost
HOST_PORT=9000

# Used to restore current dir if using command line
CURRENT_PATH=$(dirname "${0}")

if [ ! -f "$PHP_BIN" ]; then
    echo "ERROR: $PHP_BIN not found"
elif [ ! -f "$PHP_INI" ]; then
    echo "ERROR: $PHP_INI not found"
else
    # Router path
    ROUTER="$CURRENT_PATH/system/boot/server.php"

    # Start built in server
    $PHP_BIN -S "$HOST_HOST:$HOST_PORT" -c $PHP_INI -t "$CURRENT_PATH" $ROUTER
fi
