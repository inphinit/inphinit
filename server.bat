@echo off

:: Setup PHP and PORT
set PHP_BIN="C:\php\php.exe"
set PHP_INI="C:\php\php.ini"
set HOST_PORT=9000

:: Router path
set ROUTER="%~dp0\system\boot\server.php"

:: Start built in server
%PHP_BIN% -S localhost:%HOST_PORT% -c %PHP_INI% %ROUTER%

:: Prevent close if PHP failed to start
pause
