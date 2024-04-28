@echo off

rem Setup PHP and PORT
set PHP_BIN=C:\php\php.exe
set PHP_INI=C:\php\php.ini
set HOST_HOST=localhost
set HOST_PORT=9000

rem Sets the project path so you can call the "server" command from any location
set DOCUMENT_ROOT=%~dp0
set DOCUMENT_ROOT=%DOCUMENT_ROOT:~0,-1%

rem Router path
set ROUTER=%DOCUMENT_ROOT%\system\boot\server.php

if not exist %PHP_BIN% (
    echo ERROR: %PHP_BIN% not found & pause
) else if not exist %PHP_INI% (
    echo ERROR: %PHP_INI% not found & pause
) else (
    rem Start built in server
    "%PHP_BIN%" -S %HOST_HOST%:%HOST_PORT% -c "%PHP_INI%" -t "%DOCUMENT_ROOT%" "%ROUTER%" || pause
)
