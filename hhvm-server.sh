#!/bin/bash

# Setup HHVM
HHVM_BIN="/usr/bin/hhvm"
PHP_INI="/etc/hhvm/php.ini"

# Setup port
HOST_PORT=9000

# Used to restore current dir if using command line
BASEDIR=$(dirname "$0")

# HHVM ini path
HHVM_INI="$BASEDIR/system/boot/hhvm.ini"

# Start HHVM server
$HHVM_BIN -m server -p $HOST_PORT \
  -d hhvm.server.source_root=$BASEDIR -c $HHVM_INI
