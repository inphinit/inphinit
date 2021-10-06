@echo off

:: Setup PHP and PORT
set PHP_BIN="C:\php\php.exe"
set PHP_INI="C:\php\php.ini"
set HOST_HOST=localhost
set HOST_PORT=9000

:: Sets the project path so you can call the "./server" command from any location
set CURRENT_PATH=%~dp0
set CURRENT_PATH=%CURRENT_PATH:~0,-1%

:: Router path
set ROUTER="%CURRENT_PATH%\system\boot\server.php"

if not exist %PHP_BIN% (
    echo ERROR: %PHP_BIN% not found
    pause
) else if not exist %PHP_INI% (
    echo ERROR: %PHP_INI% not found
    pause
) else (
    :: Start built in server
    %PHP_BIN% -S "%HOST_HOST%:%HOST_PORT%" -c %PHP_INI% -t "%CURRENT_PATH%" %ROUTER% || pause
)
