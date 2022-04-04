## About

PHP framework, routes, controllers and views

## Requirements

1. PHP 5.3.0+
1. Multibyte String (GD also) (optional, only used in `Inphinit\Helper::toAscii`)
1. libiconv (optional, only used in `Inphinit\Helper::toAscii`)
1. fileinfo (optional, only used in `Inphinit\File::mime`)
1. Apache or Nginx or IIS for production

For check requirements see [Check requirements](#check-requirements)

## Getting start

1. Have two method for install
1. First method is using composers
1. Download [Composer](http://getcomposer.org/doc/00-intro.md) and install
1. For create an project in Windows:
    ```
    cd c:\wamp\www\
    composer create-project inphinit/inphinit [project_name]
    ```
1. Or (if no using Wamp/Xampp/easyphp)
    ```
    cd c:\Users\[username]\Documents\
    composer create-project inphinit/inphinit [project_name]
    ```
1. Install in Unix-like:
    ```
    cd /var/www/
    php composer.phar create-project inphinit/inphinit [project_name]
    ```
1. Or (if no using Apache)
    ```
    cd /home/
    php composer.phar create-project inphinit/inphinit [project_name]
    ```
1. Alternate is download GIT repository and copy content from zip file to folder project (don't clone `master` for production use), clone last release example:
    ```
    git clone -b 0.5.17 --recurse-submodules https://github.com/inphinit/inphinit.git [project_name]
    cd [project_name]
    ```

> **Note:** Don't use composer dev-master (eg. `create-projet inphinit/inphinit:dev-master`), to collaborate, prefer to clone with GIT, example:
>
> ```
> git clone --recurse-submodules https://github.com/inphinit/inphinit.git inphinit
> cd inphinit
> ```

## Apache

1. If using Apache (or Xampp, Wamp, Easyphp, etc) only navigate to `http://localhost/[project_name]/`
1. Navigate `http://localhost/[project_name]/generate-htaccess.php` for create `.htaccess`

## PHP built-in web server in Windows

1. Navigate with explorer.exe to project folder
1. Find and edit server.bat, change `php.exe` path and `php.ini` for your php path:
    ```
    set PHP_BIN="C:\php\php.exe"
    set PHP_INI="C:\php\php.ini"
    set HOST_PORT=9000
    ```
1. Save the edition and run `server.bat`
1. Open webbrowser and navigate to `http://localhost:9000`

## PHP built-in web server in Linux and Mac (or Unix-like)

1. If using Linux or Mac navigate to project folder and run using terminal:
    ```
    cd /home/[project_name]/
    php -S localhost:9000 system/boot/server.php
    ```
1. Open web-browser and navigate to `http://localhost:9000`
1. Or edit `server`
    ```
    #!/bin/bash
    PHP_BIN="/usr/bin/php"
    PHP_INI="/etc/php5/cli/php.ini"
    HOST_PORT=9000
    ```
1. Save edition and run `./server`

## Routing

1. In folder `[project_name]/system/` find `main.php` and put something like this:
    ```php
    Route::set('GET', '/foo', 'MyController:action');
    ```
1. In `[project_name]/system/application/Controller/` folder create an file with this name `MyController.php` (case sensitive)
1. Put this content:
    ```php
    <?php
    namespace Controller;

    use Inphinit\Viewing\View;

    class MyController
    {
        public function action()
        {
            $data = array( 'foo' => 'Hello', 'Baz' => 'World!' );
            View::render('myview', $data);
        }
    }
    ```
1. In `[project_name]/system/application/View/` create file with this name `myview.php` (case sensitive) and put:
    ```php
    <p><?php echo $foo, ' ', $baz; ?></p>
    ```

1. Navigate to `http://localhost:9000/foo` or `http://localhost/[project_name]/foo`

## Check requirements

For check requirements navigate with your web-browser to `http://localhost:9000/check.php` or `http://localhost/[project_name]/check.php`

## Development vs production

For setup access `[project_name]/system/application/Config/config.php` with your text editor and change `development` key to `true` or `false`:

```php
<?php
return array(
    'appdata_expires' => 86400,
    'development'     => true,
    'maintenance'     => false
);
```

## Nginx

For create nginx config run with terminal:

```
cd /home/[project_name]/
php generate-nginx.php
```

And copy content to clipboard and adjust `nginx.conf`

## IIS

Install PHP:

- Download PHP: http://windows.php.net/download/
- Install PHP to `%SYSTEMROOT%\php` (like `c:\php`, `d:\php`)

### IIS Express

Installing in IIS Express:

- Navigate to `%USERPROFILE%\Documents\IISExpress\config`
- Edit `applicationHost.config`
- And put in file this:
    ```xml
    <location path="WebSite1">
        <system.webServer>
            <handlers>
                <add
                    name="PHP_via_FastCGI"
                    path="*.php"
                    verb=""
                    modules="FastCgiModule"
                    scriptProcessor="c:\PHP\php-cgi.exe"
                    resourceType="Either" />
            </handlers>
        </system.webServer>
    </location>
    ```
- Restart IIS
- Copy contents in `%USERPROFILE%\Documents\My Web Sites\WebSite1`
- You can create a new website, so just change `WebSite1` to the new folder.

For more details in https://msdn.microsoft.com/en-us/library/hh994590(v=ws.11).aspx

### Default IIS

- Navigate to `%windir%\System32\inetsrv\config\`
- Edit `applicationHost.config` (requires Administrator Privileges)
- And put in file this like this:
    ```xml
    <defaultDocument enabled="true">
        <files>
            <add value="index.php" />
            <add value="Default.htm" />
            <add value="Default.asp" />
            <add value="index.htm" />
            <add value="index.html" />
            <add value="iisstart.htm" />
        </files>
    </defaultDocument>

    <fastCgi>
        <application
            fullPath="C:\php\php-cgi.exe"
            monitorChangesTo="C:\php\php.ini"
            activityTimeout="300"
            requestTimeout="300"
            instanceMaxRequests="10000">
            <environmentVariables>
                <environmentVariable name="PHPRC" value="C:\php\" />
                <environmentVariable name="PHP_FCGI_MAX_REQUESTS" value="10000" />
            </environmentVariables>
        </application>
    </fastCgi>
    ```
- And after put:
    ```xml
    <handlers accessPolicy="Read, Script">
        <add
            name="PHP_via_FastCGI"
            path="*.php"
            verb="*"
            modules="FastCgiModule"
            scriptProcessor="C:\php\php-cgi.exe"
            resourceType="Either" />
    ```
