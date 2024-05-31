## About

PHP framework, routes, controllers and views

## Requirements

1. PHP 5.4+ (PHP 8.2 or higher is recommended, see https://www.php.net/supported-versions.php)
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
    ```bash
    cd c:\wamp\www\
    composer create-project inphinit/inphinit [project_name]
    ```
1. Or (if no using Wamp/Xampp/easyphp)
    ```bash
    cd c:\Users\[username]\Documents\
    composer create-project inphinit/inphinit [project_name]
    ```
1. Install in Unix-like:
    ```bash
    cd /var/www/
    php composer.phar create-project inphinit/inphinit [project_name]
    ```
1. Or (if no using Apache)
    ```bash
    cd /home/
    php composer.phar create-project inphinit/inphinit [project_name]
    ```
1. Alternate is download Git repository and copy content from zip file to folder project (don't clone `master` for production use), clone last release example:
    ```bash
    git clone -b 0.6.1 --recurse-submodules https://github.com/inphinit/inphinit.git [project_name]
    cd [project_name]
    ```

> **Note:** Don't use composer dev-master (eg. `create-project inphinit/inphinit:dev-master`), to collaborate, prefer to clone with GIT, example:
>
> ```bash
> git clone --recurse-submodules https://github.com/inphinit/inphinit.git inphinit
> cd inphinit
> ```

## PHP built-in web server in Windows

1. Navigate with `explorer.exe` to project folder
1. Find and edit `server.bat`, change `PHP_BIN` and `PHP_INI`:
    ```batch
    rem Setup PHP and PORT
    set PHP_BIN=C:\php\php.exe
    set PHP_INI=C:\php\php.ini
    ```
1. Save the edition and run `server.bat`
1. Open web-browser and navigate to `http://localhost:9000`

## PHP built-in web server in Linux and macOS (or Unix-like)

1. Navigate to your project and find and edit `./server` file, change, change `PHP_BIN` and `PHP_INI`:
    ```bash
    #!/bin/bash
    PHP_BIN="/usr/bin/php"
    PHP_INI="/etc/php.ini"
    HOST_PORT=9000
    ```
1. Save edition and run `./server`
1. Open web-browser and navigate to `http://localhost:9000`

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

## Group routes

In `main.php` put `use Inphinit\Routing\Group;` on top document, then you can use `Group` class. See examples:

Group routes by domain:

```php
// The routes in the callback, called by the "then" method, will be executed when accessing the localhost domain
Group::create()->domain('localhost')->then(function () {
    Route::set(...);
});

// The routes in the callback, called by the "then" method, will be executed when accessing a subdomain from .site.com domain
Group::create()->domain('{:\w+:}.site.com')->then(function ($domain) {
    Route::set(...);
});
```

```php
// The routes in the callback, called by the "then" method, will be executed when accessing using HTTPS
Group::create()->secure(Group::SECURE)->then(function () {
    Route::set(...);
});

// The routes in the callback, called by the "then" method, will be executed when accessing using HTTP
Group::create()->secure(Group::NONSECURE)->then(function () {
    Route::set(...);
});
```

Group routes by domain:

```php
// The routes in the callback, called by the "then" method, will be executed when accessing path urls starts with /foo/
Group::create()->path('/foo/')->then(function () {
    Route::set(...);
});

// The routes in the callback, called by the "then" method, will be executed when accessing path urls starts with /assets/<any word with A-Z0-9_ characters>
Group::create()->path('/assets/{:\w+:}/')->then(function ($subPath) {
    Route::set(...);
});

// The routes in the callback, called by the "then" method, will be executed when path and route matches with path and domain
Group::create()->domain('localhost')->path('/foo/')->then(function () {
    Route::set(...);
});
```

Prefix controllers namespace on Group:

```php
Group::create()->prefixNS('Baz.Bar')->path('/foo/')->then(function () {
    Route::set('GET', '/',         'Foo:index');
    Route::set('GET', '/about',    'Foo:about');
    Route::set('GET', '/contact',  'Foo:contact');
    Route::set('GET', '/inphinit', 'Inphinit:index');
});
```

Equivalent to:

```php
Route::set('GET', '/foo/',         'Baz.Bar.Foo:index');
Route::set('GET', '/foo/about',    'Baz.Bar.Foo:about');
Route::set('GET', '/foo/contact',  'Baz.Bar.Foo:contact');
Route::set('GET', '/foo/inphinit', 'Baz.Bar.Inphinit:index');
```

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

# Deploy for Apache server

In `.htaccess`, adjust the path in the `ErrorDocument` according to the level at which your application is accessed at the URL, if it is at the root keep the `ErrorDocument`s as:

```
ErrorDocument 403 /index.php/RESERVED.INPHINIT-403.html
ErrorDocument 500 /index.php/RESERVED.INPHINIT-500.html
ErrorDocument 501 /index.php/RESERVED.INPHINIT-501.html
```

If the application is in a subfolder, for example `https://foo.com/application/`, then it should look like:

```
ErrorDocument 403 /application/index.php/RESERVED.INPHINIT-403.html
ErrorDocument 500 /application/index.php/RESERVED.INPHINIT-500.html
ErrorDocument 501 /application/index.php/RESERVED.INPHINIT-501.html
```

If you intend to use authorization, uncomment the following lines in `.htaccess`:

```
# RewriteCond %{HTTP:Authorization} .
# RewriteRule . - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```

# Deploy for NGINX server

Use the following configuration as a starting point for configuring your nginxconf (you will probably need specific adjustments for your server):

```
server {
    listen 443;
    listen [::]:443;
    server_name foobar.com;
    root /etc/www/sites/application;

    index index.php;

    charset utf-8;

    location / {
        # Redirect page errors to route system
        error_page 403 /index.php/RESERVED.INPHINIT-403.html;
        error_page 500 /index.php/RESERVED.INPHINIT-500.html;
        error_page 501 /index.php/RESERVED.INPHINIT-501.html;

        try_files /public$uri /index.php?$query_string;

        location = / {
            try_files $uri /index.php?$query_string;
        }

        location ~ /\. {
            try_files /index.php$uri /index.php?$query_string;
        }

        location ~ \.php$ {
            # Replace by your FPM or FastCGI
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;

            fastcgi_index index.php;
            include fastcgi_params;

            set $inphinit_suffix "";

            if ($uri != "/index.php") {
                set $inphinit_suffix "/public";
            }

            fastcgi_param SCRIPT_FILENAME $realpath_root$inphinit_suffix$fastcgi_script_name;
        }
    }
}
```
