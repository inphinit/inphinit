- [Installing](#installing)
- [Testing](#testing)
- [NGINX](#nginx)
- [Folder structure](#folder-structure)
- [Creating routes](#creating-routes)
- [Grouping routes](#grouping-routes)
- [Route and URL patterns](#route-and-url-patterns)

## Decisions and what's next

The objective of this framework has always been to be as efficient as possible, however something that always worried me were debugging problems, despite there being several tools, I always aimed for something simple, but of course even those who are working for the first time see the error, So this past year I made the following decisions:

- In development mode you should work strictly, checking any detail
- Change the way routes work, to make them faster and also be able to predict failures, when used in development
- Some typing errors can make certain PHP features not respond in a timely manner, such as autoload, so developer mode will preload everything you need before any script disrupts the process, allowing debugging to locate and display exactly which line the error is on.

All of these decisions are embedded in the framework, some of which have already been added to _version 1.x_, to make it easier to port the project to the future version of the framework.

All core internal APIs are already supported by version 2.x, some have been completely rewritten, focusing on simplicity and performance, so if you are migrating from _1.x_ it is likely that you will need to rewrite some things. Fortunately, most classes are much simpler to use.

## What we have already achieved

If you are using _version 0.5_ and cannot yet migrate to _version 2.x_, it is highly recommended that you migrate to _version 1.x_.

Version _0.5_ already had excellent performance, but it was still possible to bring some performance features from _version 2.x_ to version 1.x_. In _version 2.x_ it is a little better, so here is an example of the tests, with development mode turned off:

Description                                             | v0.5.x                       | v1.x                         | v2.x
---                                                     | ---                          | ---                          | --- 
Time taken for tests                                    | 0.528 seconds                | 0.429 seconds                | 0.391 seconds
Requests per second (mean)                              | 1892.46 [#/sec]              | 2330.74 [#/sec]              | 2557.07 [#/sec]
Time per request (mean)                                 | 5.284 [ms]                   | 4.290 [ms]                   | 3.911 [ms]
Time per request (mean, across all concurrent requests) | 0.528 [ms]                   | 0.429 [ms]                   | 0.391 [ms]
Transfer rate                                           | 373.32 [Kbytes/sec] received | 459.77 [Kbytes/sec] received | 504.42 [Kbytes/sec] received

In addition to the improved execution time, it is noted that _version 2.x_ was able to process an average of 220 more requests per second than _version 1.x_, and compared to _0.5.x_, it was able to process 600 more requests per second.

## About documentation

Something I will change is the documentation, the Github Wiki worked for a while but I noticed some issues:

- The menu generated on the github wiki is not that intuitive and I noticed that even some experienced programmers were unable to navigate there
- Organizing the content was not as easy as I wanted, many things are manual, which took a lot of time to edit a few things
- Github Desktop conflicts with wiki-type repositories, it's an [old bug](https://github.com/desktop/desktop/issues/3839#issue-290340050)

The documentation will soon be available, initially in English and Portuguese.

## Installing

> **Note:** To install _version 1.x_ go to: https://github.com/inphinit/inphinit/tree/1.x

It is highly recommended to migrate to _version 2.x_ to maintain support for future versions of PHP. To install it you must have at least _PHP 5.4_, but it is recommended that you use _PHP 8_ due to PHP support issues, read:

- https://www.php.net/supported-versions.php
- https://www.php.net/eol.php

After installing PHP, you can install Inphinit using Composer or using Git.

If you use composer, run the command (more details in https://getcomposer.org/doc/03-cli.md):

```bash
php composer.phar create-project inphinit/inphinit my-application
```

If you use composer global, run the command:

```bash
composer create-project inphinit/inphinit my-application
```

Installing using Git:

```bash
git clone --recurse-submodules https://github.com/inphinit/inphinit.git my-application
cd my-application
```

## Testing

After navigating to the folder you must execute the following command, if you want to use [PHP built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php):

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

> **Note:** For FPM use `fastcgi_pass unix:/var/run/php/php<version>-fpm.sock` (replace `<version>` by PHP version in your server)

## Folder structure

```bash
├───.htaccess        # Apache web server configuration
├───index.php        # Only change the values of the constants, and only if necessary
├───server           # Shortcut to start the built-in web server on Linux and macOS
├───server.bat       # Shortcut to start the built-in web server on Windows
├───web.config       # IIS web server configuration
├───public/          # In this folder you can place static files or PHP scripts that will be independent
└───system/          # Folder containing your application
    ├───boot/        # Contain settings for inphinit_autoload, similar to composer_autoload
    ├───configs/     # Contain varied configuration files, it is recommended that you do not version this folder
    │   └───app.php  # Don't add new keys, just change the values of existing ones if necessary
    ├───controllers/ # Must contain the classes that will be controllers used in the routes
    ├───vendor/      # Contain third-party packages and the framework
    ├───views/       # Should contain your views
    ├───dev.php      # It has the same purpose as the "main.php" script, but it will only work in development mode
    ├───errors.php   # It should contain error page settings, such as when a 404 or 405 error occurs, you can call static files or use views
    └───main.php     # This is the main file for routes and events, it will be available in development mode and production mode
```

In development mode, the `system/dev.php` script will always be executed first, then `system/main.php` will be executed, and if an error occurs, such as 404 or 405, the last script to be executed will be `system/errors.php`

## Creating routes

To create a new route, edit the `system/main.php` file, if you want the route to only be available in development mode, then edit the `system/dev.php` file.

The route system supports _controllers_, [_callables_](https://www.php.net/manual/en/language.types.callable.php) and [_anonymous functions_](https://www.php.net/manual/en/functions.anonymous.php), examples:

```php
<?php

// anonymous functions
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// callable function
$app->action('GET', '/function', 'foobar');

// callable class static method (Note: autoload will include the file)
$app->action('GET', '/class-static-method', ['MyNameSpace\Foo\Bar', 'hello']);

// callable class method
$foo = new Sample;
$app->action('GET', '/class-method', [$foo, 'hello']);


// do not add the Controller prefix, the framework itself will add
$app->action('GET', '/controller', 'Boo\Bar::xyz');

/**
 * Controller from `./system/controllers/Boo/Bar.php`:
 *
 * <?php
 * namespace Controller\Boo;
 *
 * class Bar {
 *    public function xyz() {
 *        ...
 *    }
 * }
 */
```

## Grouping routes

The route grouping system is now much simpler, it is based on the complete URL, and you can use the `*` wildcard character and also the same patterns available for routes, examples:

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
$app->scope('*://*/blog/', function ($app, $params) {
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

// Routes will only be added if you are accessing mysite2.org host
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
`nospace` | `$app->action('GET', '/foo/<nospace:nospace>', ...);` | Accepts any characters expcet spaces, like white-spaces (`%20`), tabs (`%0A`) and others (see about `\S` in regex)
`uuid` | `$app->action('GET', '/bar/<barcode:alnum>', ...);`      | Only accepts parameters with uuid format and `$params` returns `['barcode' => ...]`
`version` | `$app->action('GET', '/baz/<api:version>', ...);`     | Only accepts parameters with _Semantic Versioning 2.x.0 (semversion)_ format and `$params` returns `['api' => ...]`

It is possible to add or modify existing patterns using the `$app->setPattern(name, regex)` method. Creating a new pattern:

```php
<?php
use Inphinit\Viewing\View;

$app->action('GET', '/about/<lang:locale>', function ($params) {
    $lang = $params['lang'];
    ...
});

$app->action('GET', '/product/<id:id>', function ($params) {
    $lang = $params['id'];
    ...
});

$app->setPattern('locale', '[a-z]{1,8}(\-[A-Z\d]{1,8})?'); // examples: en, en-US, en-GB, pt-BR, pt
$app->setPattern('id', '[A-Z]\d+'); // examples: A0001, B002, J007
```

Modifying an existing pattern:

```php
<?php

// Replace semversion by <major>.<minor>.<revision>.<build>
$app->setPattern('version', '\d+\.\d+.\d+.\d+');

// Replace semversion by <major>.<minor> (maybe it's interesting for web APIs)
$app->setPattern('version', '\d+\.\d+');
```
