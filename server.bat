@echo off

:: Setup PHP and PORT
set PHP_BIN="C:\php\php.exe"
set PHP_INI="C:\php\php.ini"
set HOST_HOST="localhost"
set HOST_PORT=9000

:: Current path
set CURRENT_PATH=%~dp0
set CURRENT_PATH=%CURRENT_PATH:~0,-1%

:: Router path
set ROUTER="%CURRENT_PATH%\system\boot\server.php"

:: Start built in server
%PHP_BIN% -S %HOST_HOST%:%HOST_PORT% -c %PHP_INI% -t %CURRENT_PATH% %ROUTER%

:: Prevent close if PHP failed to start
pause
