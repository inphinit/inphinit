<p align="center">
<a href="https://packagist.org/packages/inphinit/inphinit"><img src="https://img.shields.io/packagist/dt/inphinit/inphinit" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/inphinit/inphinit"><img src="https://img.shields.io/packagist/v/inphinit/inphinit" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/inphinit/inphinit"><img src="https://img.shields.io/packagist/l/inphinit/inphinit" alt="License"></a>
</p>

- [Installing](#installing)
- [Testing](#testing)
- [NGINX](#nginx)
- [Folder structure](#folder-structure)
- [Creating routes](#creating-routes)
- [Grouping routes](#grouping-routes)
- [Route and URL patterns](#route-and-url-patterns)
- [Documentation](#documentation)

## Installing

Requirements:

1. Recommended: *PHP 8* (see the currently supported versions at https://www.php.net/supported-versions.php)
   - Minimum: *PHP 5.4* (backward compatibility is preserved for environments with upgrade limitations)
   - If you need a full-featured server on Windows or macOS, consider using WampServer, XAMPP, Laragon, EasyPHP, or AMPPS.
1. (Optional) The Intl PHP extension, required for the `Inphinit\Utility\Strings` class.
1. (Optional) The COM or cURL PHP extension, required for the `Inphinit\Filesystem\Size` class.

After installing PHP, you can install Inphinit via Composer or Git.

To install via Composer, run the command (see more details at https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit my-application
```

If Composer is installed globally, use:

```bash
composer create-project inphinit/inphinit my-application
```

To install via Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testing

After navigating to your project directory, run the following command to start the [PHP built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

Then access it in your browser at `http://localhost:5000/`.

## NGINX

If you want to use NGINX, you can configure your `nginx.conf` as follows:

```none
location / {
    root /home/foo/bar/my-application;

    # Redirect page errors to route system
    error_page 403 /index.php/RESERVED.INPHINIT-403.html;
    error_page 500 /index.php/RESERVED.INPHINIT-500.html;

    try_files /public$uri /index.php?$query_string;

    location = / {
        try_files $uri /index.php?$query_string;
    }

    location ~ /\. {
        try_files /index.php$uri /index.php?$query_string;
    }

    location ~ \.php$ {
        # Replace with your FPM or FastCGI address
        fastcgi_pass 127.0.0.1:9000;

        fastcgi_index index.php;
        include fastcgi_params;

        set $teeny_suffix "";

        if ($uri != "/index.php") {
            set $teeny_suffix "/public";
        }

        fastcgi_param SCRIPT_FILENAME $realpath_root$teeny_suffix$fastcgi_script_name;
    }
}
```

> **Note:** For PHP-FPM (FastCGI Process Manager), use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (replace `<version>` with your PHP version).

## Folder structure

```bash
├───.htaccess                  # Apache configuration file for handling requests and routing
├───index.php                  # Main entry point; modify constants only if necessary
├───server                     # Shortcut to start the PHP built-in server on Linux or macOS
├───server.bat                 # Shortcut to start the PHP built-in server on Windows
├───web.config                 # IIS configuration file for URL rewriting and routing
├───public/                    # Contains static assets and standalone PHP scripts outside the main app flow
│   └───.htaccess              # Apache configuration for serving static files or standalone scripts
└───system/                    # Contains all application source code and configuration
    ├───dev.php                # Entry point for development mode; runs before main.php
    ├───errors.php             # Handles error pages (e.g., 404, 405) and can render static files or views
    ├───main.php               # Main routing and event definition file for all environments
    ├───boot/                  # Autoload and initialization settings (similar to Composer’s autoload)
    │   ├───importpackages.php # Defines additional package imports for the autoloader
    │   └───namespaces.php     # Maps namespaces to directories for class autoloading
    ├───configs/               # Contains configuration files; avoid committing sensitive data to version control
    │   ├───app.php            # Application configuration; modify existing values as needed
    │   └───debug.php          # Debug configuration; modify existing values as needed
    ├───Controllers/           # Contains controller classes referenced in route definitions
    ├───storage/               # Used for temporary files, logs, or cache data
    ├───vendor/                # Third-party dependencies and the framework core
    └───views/                 # Contains templates and view files
```

In development mode, the `system/dev.php` script is always executed first, followed by `system/main.php`. If an error occurs (e.g., 404 or 405), the last script to run will be `system/errors.php`.

## Creating routes

To create a new route, edit the `system/main.php` file. If you want the route to be available only in development mode, edit the `system/dev.php` file instead.

The routing system supports *controllers*, [*callables*](https://www.php.net/manual/en/language.types.callable.php), and [*anonymous functions*](https://www.php.net/manual/en/functions.anonymous.php). For example:

```php
<?php

// Anonymous function
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// Callable function
$app->action('GET', '/function', 'foobar');

// Callable class static method — the autoloader automatically includes the class file
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// Callable class method
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);

// Don't need to include the Controllers namespace prefix — the framework automatically prepends it
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controller from `./system/Controllers/Boo/Bar.php`:
 *
 * <?php
 * namespace Controllers\Boo;
 *
 * class Bar {
 *    public function xyz() {
 *        ...
 *    }
 * }
 */
```

## Grouping routes

The route grouping system is simple and flexible. It is based on the full URL or path and supports the `*` wildcard, as well as the same patterns available for routes.

```php
<?php

/*
 * Routes will only be added if the path starts with /blog/
 *
 * Examples:
 *
 * http://localhost:5000/blog/
 * http://localhost:5000/blog/post
 * http://localhost:5000/blog/search
 */
$app->scope('/blog/', function ($app, $params) {
    $app->action('GET', '/', function () { ... });
    $app->action('POST', '/post', function () { ... });
    $app->action('GET', '/search', function () { ... });
});

// Routes will only be added if you are accessing via HTTPS
$app->scope('https://*', function ($app, $params) {
    ...
});

// Routes will only be added if you are accessing via HTTP
$app->scope('http://*', function ($app, $params) {
    ...
});

// Routes will only be added when the request host is mysite2.org
$app->scope('*://mysite2.org/', function ($app, $params) {
    ...
});

// Routes will only be added if you are accessing a subdomain of main.org, e.g., site1.main.org
$app->scope('*://*.main.org/', function ($app, $params) {
    ...
});

// Using a pattern to capture the subdomain:
$app->scope('*://<subdomain>.main.org/', function ($app, $params) {
    $subdomain = $params['subdomain'];
    ...
});

// Using a pattern to capture path parameters:
$app->scope('*://*/users/<id:num>/<user>', function ($app, $params) {
    $id = $params['id'];
    $username = $params['user'];
    ...
});
```

See more examples in the `system/dev.php` file.

## Route and URL patterns

| Type      | Example                                               | Description                                                                                           |
| --------- | ----------------------------------------------------- | ----------------------------------------------------------------------------------------------------- |
| `alnum`   | `$app->action('GET', '/baz/<video:alnum>', ...);`     | Accepts only alphanumeric parameters; `$params` returns `['video' => ...]`                            |
| `alpha`   | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`  | Accepts only alphabetic parameters; `$params` returns `['name' => ...]`                               |
| `decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`   | Accepts only decimal number parameters; `$params` returns `['price' => ...]`                          |
| `num`     | `$app->action('GET', '/foo/<id:num>', ...);`          | Accepts only integer parameters; `$params` returns `['id' => ...]`                                    |
| `nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Accepts any characters except spaces, such as `%20` or tabs (see the `\S` regex pattern)              |
| `uuid`    | `$app->action('GET', '/bar/<barcode:uuid>', ...);`    | Accepts UUID-formatted parameters; `$params` returns `['barcode' => ...]`                             |
| `version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Accepts parameters in *Semantic Versioning 2.0.0 (SemVer)* format; `$params` returns `['api' => ...]` |

You can add or modify existing patterns using the `$app->setPattern(name, regex)` method. Example:

```php
<?php
use Inphinit\Viewing\View;

$app->action('GET', '/about/<lang:locale>', function ($params) {
    $lang = $params['lang'];
    ...
});

$app->action('GET', '/product/<id:customid>', function ($params) {
    $id = $params['id'];
    ...
});

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // examples: en, en-US, en-GB, pt-BR, pt
$app->setPattern('customid', '[A-Z]\d+'); // examples: A0001, B002, J007
```

Modifying an existing pattern:

```php
<?php

// Replace SemVer with <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+\.\d+\.\d+');

// Replace SemVer with <major>.<minor> (useful for web APIs)
$app->setPattern('version', '\d+\.\d+');
```

## Documentation

- English: https://inphinit.github.io/en/docs/
- Portuguese: (Coming soon)
- API Reference: https://inphinit.github.io/api/

The documentation is maintained in a separate [GitHub repository](https://github.com/inphinit/inphinit.github.io).
