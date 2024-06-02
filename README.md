# Inphinit 2.0

- [Installing](#installing)
- [Testing](#testing)
- [NGINX](#nginx)
- [Folder structure](#folder-structure)
- [Routing](#routing)
- [Grouping routes](#grouping-routes)

## Decisions and what's next

The objective of this framework has always been to be as efficient as possible, however something that always worried me were debugging problems, despite there being several tools, I always aimed for something simple, but of course even those who are working for the first time see the error, So this past year I made the following decisions:

- In development mode you should work strictly, checking any detail
- Change the way routes work, to make them faster and also be able to predict failures, when used in development
- Some typing errors can make certain PHP features not respond in a timely manner, such as autoload, so developer mode will preload everything you need before any script disrupts the process, allowing debugging to locate and display exactly which line the error is on.

All of these decisions are embedded in the framework, some of which have already been added to version 0.6, to make it easier to port the project to the future version of the framework.

All of the routes and basic application are already established, but other internal APIs, for other uses, are still under development, so we are entering this phase, and within 2 weeks at most the first beta will be released, where I will not include any more new functionality, it will be a series of corrections and regressions.

## What we have already achieved

I have always valued performance and simplicity, part of what was implemented in _Inphinit 2.0_ has already been ported to _0.6_, which provided a great improvement in these versions before the release of 2.0, and even though _version 0.5_ is very efficient, the leap in performance It was incredible from _version 0.6_ onwards. In _version 2.0_ it is a little better, so here is an example of the tests, with development mode turned off:

Description                                              | 0.5.19                       | 0.6.x                        | 2.0
---                                                      | ---                          | ---                          | --- 
Time taken for tests:                                    | 0.528 seconds                | 0.429 seconds                | 0.391 seconds
Requests per second (mean):                              | 1892.46 [#/sec]              | 2330.74 [#/sec]              | 2557.07 [#/sec]
Time per request (mean):                                 | 5.284 [ms]                   | 4.290 [ms]                   | 3.911 [ms]
Time per request (mean, across all concurrent requests): | 0.528 [ms]                   | 0.429 [ms]                   | 0.391 [ms]
Transfer rate:                                           | 373.32 [Kbytes/sec] received | 459.77 [Kbytes/sec] received | 504.42 [Kbytes/sec] received

In addition to the improved execution time, it is noted that _version 2.0_ was able to process an average of 220 more requests per second than _0.6_ version, and compared to 0.5.x, it was able to process 600 more requests per second.

## About documentation

Something I will change is the documentation, the Github Wiki worked for a while but I noticed some issues:

- The menu generated on the github wiki is not that intuitive and I noticed that even some experienced programmers were unable to navigate there
- Organizing the content was not as easy as I wanted, many things are manual, which took a lot of time to edit a few things
- Github Desktop conflicts with wiki-type repositories, it's an [old bug](https://github.com/desktop/desktop/issues/3839#issue-290340050)

The next step will be to create wizard-style pages, with a menu that makes navigation easier, and I intend to write in 3 different languages, so I will migrate all the content to a new open platform, which will make it much easier for the framework's users.

## Installing

> **Note:** To install _version 0.6_ go to: https://github.com/inphinit/inphinit/tree/1.x

Note that we are still in the development phase, and in 2 weeks we intend to launch the first beta, which will be available via composer, _version 2.0_ is not yet recommended for production, so prefer to use it only for testing or criticism that you wish to do during this step.

To install it you must have at least PHP 5.4, but it is **recommended that you use PHP 8** due to PHP support issues, read:

> - https://www.php.net/supported-versions.php
> - https://www.php.net/eol.php

After install PHP, you need to have Git on your machine, so run the following commands:

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
    error_page 403 /index.php/RESERVED.TEENY-403.html;
    error_page 500 /index.php/RESERVED.TEENY-500.html;
    error_page 501 /index.php/RESERVED.TEENY-501.html;

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

## Folder structure

```bash
├───.htaccess       # (Apache web server configuration)
├───index.php       # (Only change the values of the constants, and only if necessary)
├───server          # (shortcut to start the built-in web server on Linux and macOS)
├───server.bat      # (shortcut to start the built-in web server on Windows)
├───web.config      # (IIS web server configuration)
├───public          # (In this folder you can place static files or PHP scripts that will be independent)
└───system          # (folder containing your application)
    ├───boot        # (contain settings for inphinit_autoload, similar to composer_autoload)
    ├───configs     # (contain varied configuration files, it is recommended that you do not version this folder)
    │   └───app.php # (Don not add, just change the values, if necessary)
    ├───controllers # (must contain the classes that will be controllers used in the routes)
    ├───vendor      # (contain third-party packages and the framework)
    ├───views       # (should contain your views)
    ├───dev.php     # (It has the same purpose as the "main.php" script, but it will only work in development mode)
    ├───errors.php  # (it should contain error page settings, such as when a 404 or 405 error occurs, you can call static files or use views)
    └───main.php    # (This is the main file for routes and events, it will be available in development mode and production mode)
```

In development mode, the `system/dev.php` script will always be executed first, only after `system/main.php` will be executed, and if an error 404 or 405 occurs, the last script to be executed will be `system/errors.php`

## Routing

The route system supports controllers, [callables](https://www.php.net/manual/en/language.types.callable.php) and [anonymous functions](https://www.php.net/manual/en/functions.anonymous.php), examples:

```php
// anonymous functions
$app->action('GET', '/closure', function () {
    return 'Hello "closure"!';
});

function foobar() {
    return 'Hello "function"!';
}

// callable function
$app->action('GET', '/function', 'foobar');

// callable class method (Note: autoload will include the file)
$app->action('GET', '/class-method', ['MyNameSpace\Foo\Bar', 'hello']);


// do not add the Controller prefix, the framework itself will add
$app->action('GET', '/class-method', 'Boo\Bar::baz');

/* Controller from `./system/controllers/Boo/Bar.php`:
 *
 * <?php
 * namespace Controller\Boo;
 *
 * class Bar {
 *    public function hello() {
 *        ...
 *    }
 * }
 */
```

## Grouping routes

The route grouping system is now much simpler, it is based on the complete URL, and you can use the `*` wildcard character and also the same patterns available for routes, examples:

```php
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

See more examples in the `my-application/system/dev.php` file
