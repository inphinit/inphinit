@echo off
set PHP_BIN="C:\php\php.exe"
set PHP_INI="C:\php\php.ini"
set HOST_PORT=9000

%PHP_BIN% -S localhost:%HOST_PORT% -c %PHP_INI% "system/boot/server.php"
pause
