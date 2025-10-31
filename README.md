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

1. See currently supported PHP versions: https://www.php.net/supported-versions.php.
    * Minimal _PHP 5.4_ (backward compatibility is maintained for users with upgrade limitations).
    * If you need a full-featured server for Windows or macOS, you can try WampServer, XAMPP, Laragon, EasyPHP, or AMPPS.
1. (Optional) Intl PHP extension to use `Inphinit\Utility\Strings` class.
1. (Optional) COM PHP extension or cURL PHP extension to use `Inphinit\Filesystem\Size` class.

After installing PHP, install Inphinit via Composer or Git.

If you use composer, run the command (more details in https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit my-application
```

If you use composer global, run the command:

```bash
composer create-project inphinit/inphinit my-application
```

Installing via Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testing

After navigating to your project directory, run the following command, if you want to use [PHP built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php):

```bash
php -S localhost:5000 -t public index.php
```

And access in your browser `http://localhost:5000/`

## NGINX

If you want to experiment with a web server such as NGINX, you can use the following example to configure your `nginx.conf`:

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
        # Replace by your FPM or FastCGI
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

> **Note:** For PHP-FPM (FastCGI Process Manager) use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (replace `<version>` by PHP version in your server)

## Folder structure

```bash
├───.htaccess                  # Apache configuration file for handling requests and routing
├───index.php                  # Main entry point; modify only constant values if necessary
├───server                     # Shortcut to start the built-in PHP web server on Linux or macOS
├───server.bat                 # Shortcut to start the built-in PHP web server on Windows
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
    ├───configs/               # Contains configuration files; Avoid committing sensitive configuration files to version control
    │   ├───app.php            # Application configuration; Only modify the existing configuration values as needed
    │   └───debug.php          # Debug configuration; Only modify the existing configuration values as needed
    ├───Controllers/           # Contains controller classes referenced in route definitions
    ├───storage/               # Used for temporary files, logs, or cache data
    ├───vendor/                # Third-party dependencies and the framework core
    └───views/                 # Contains templates and view files
```

In development mode, the `system/dev.php` script will always be executed first, then `system/main.php` will be executed, and if an error occurs, such as 404 or 405, the last script to be executed will be `system/errors.php`

## Creating routes

To create a new route, edit the `system/main.php` file, if you want the route to only be available in development mode, then edit the `system/dev.php` file.

The route system supports _controllers_, [_callables_](https://www.php.net/manual/en/language.types.callable.php) and [_anonymous functions_](https://www.php.net/manual/en/functions.anonymous.php), examples:

```php
<?php

// Anonymous functions
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// Callable function
$app->action('GET', '/function', 'foobar');

// Callable class static method — The autoloader automatically includes the class file
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// Callable class method
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);


// Don't include the Controllers namespace prefix — the framework automatically prepends it
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

The route grouping system is now much simpler, it's based on the complete URL or path, and you can use the `*` wildcard character and also the same patterns available for routes, examples:

```php
<?php

/*
 * Routes will only be added if the path starts with /blog/
 * 
 * Samples:
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

// Routes will only be added if you are accessing a subdomain from main.org, like: site1.main.org
$app->scope('*://*.main.org/', function ($app, $params) {
    ...
});

// Using pattern to get the subdomain:
$app->scope('*://<subdomain>.main.org/', function ($app, $params) {
    $subdomain = $params['subdomain'];
    ...
});

// Using pattern to get path:
$app->scope('*://*/users/<id:num>/<user>', function ($app, $params) {
    $id = $params['id'];
    $username = $params['user'];
    ...
});
```

See more examples in the `system/dev.php` file

## Route and URL patterns

Type | Example | Description
---|---|---
`alnum` | `$app->action('GET', '/baz/<video:alnum>', ...);`       | Only accepts parameters with alpha-numeric format and `$params` returns `['video' => ...]`
`alpha` | `$app->action('GET', '/foo/bar/<name:alpha>', ...);`    | Only accepts parameters with alpha format and `$params` returns `['name' => ...]`
`decimal` | `$app->action('GET', '/baz/<price:decimal>', ...);`   | Only accepts parameters with decimal format and `$params` returns `['price' => ...]`
`num` | `$app->action('GET', '/foo/<id:num>', ...);`              | Only accepts parameters with integer format and `$params` returns `['id' => ...]`
`nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Accepts any characters except spaces, like white-spaces (`%20`), tabs (`%0A`) and others (see about `\S` in regex)
`uuid` | `$app->action('GET', '/bar/<barcode:uuid>', ...);`      | Only accepts parameters with uuid format and `$params` returns `['barcode' => ...]`
`version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Only accepts parameters with _Semantic Versioning 2.0.0 (SemVer)_ format and `$params` returns `['api' => ...]`

It is possible to add or modify existing patterns using the `$app->setPattern(name, regex)` method. Creating a new pattern:

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

// Replace SemVer by <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+\.\d+\.\d+');

// Replace SemVer by <major>.<minor> (maybe it's interesting for web APIs)
$app->setPattern('version', '\d+\.\d+');
```

## Documentation

* English: https://inphinit.github.io/en/docs/
* Português: (Em breve)
* API: https://inphinit.github.io/api/

The documentation is maintained in its own [GitHub repository](https://github.com/inphinit/inphinit.github.io).