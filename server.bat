@echo off

rem Setup PHP and PORT
set PHP_BIN=C:\php\php.exe
set PHP_INI=C:\php\php.ini

set HOST_HOST=localhost
set HOST_PORT=9000

rem Sets the project path so you can call the "server" command from any location
set CURRENT_PATH=%~dp0
set CURRENT_PATH=%CURRENT_PATH:~0,-1%

rem Sets document root for application
set DOCUMENT_ROOT=%CURRENT_PATH%\system\public

rem Router path
set ROUTER=%CURRENT_PATH%\system\boot\server.php

if not exist %PHP_BIN% (
    echo ERROR: %PHP_BIN% not found & pause
) else if not exist %PHP_INI% (
    echo ERROR: %PHP_INI% not found & pause
) else (
    rem Start built in server
    "%PHP_BIN%" -S %HOST_HOST%:%HOST_PORT% -c "%PHP_INI%" -t "%DOCUMENT_ROOT%" "%ROUTER%" || pause
)
