<?php
namespace Controllers;

use Inphinit\App;
use Inphinit\Diagnostics\Checkup;
use Inphinit\Packages;
use Inphinit\Viewing\View;

class HomeController
{
    public function index()
    {
        $version = Packages::info('inphinit/framework', Packages::INFO_VERSION);

        View::data('environment', App::config('environment'));

        $items = [
            [
                'link' => 'https://inphinit.github.io/en/docs/routing/',
                'title' => 'Routing',
                'content' => 'Routing provides a means to map URL paths to controller actions or callable functions, and you can scope routes based on URL matching. Both routes and route scopes support patterns'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/controllers.html',
                'title' => 'Controllers',
                'content' => 'Controllers are a means of organizing logic, separating by namespaces and classes, they must be stored in the <code>system/Controllers/</code> folder.'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/routing/resource.html',
                'title' => 'Resource routes',
                'content' => 'Resource routes are a way of defining a set of routes that map the various CRUD (Create, Read, Update, and Delete) operations to a controller with resources. By using resource routes, you can quickly define all the routes needed for your application in a single line of code'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/routing/implicit-route-controllers.html',
                'title' => 'Implicity route controllers',
                'content' => 'Implicit controllers are a way to turn class methods into routes, based on the name, being able to organize the logic and routes at the same time in the controller itself'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/http/request.html',
                'title' => 'HTTP',
                'content' => 'Some helper functions are provided to work with HTTP requests and responses, including a mechanism for work with content negotiation (e.g., Accept, Accept-Encoding, TE headers)'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/dom/',
                'title' => 'DOM',
                'content' => 'The DOM (Document Object Model) is an API that represents and interacts with any HTML or XML-based markup language document. We provide a way to use CSS selectors in documents on the server and in debug mode you will be able to identify problems in the structure of a loaded document (XML or HTML)'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/debugging.html',
                'title' => 'Debugging',
                'content' => 'Debug mode is a way of detecting faults before going into production, directly on the web page, from which you will receive a detailed message, with a preview of the code snippet, indicating the line closest to the fault. You will also be able to predict route failures, route scopes and analyze problems in XML and HTML documents in detail'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/configurations.html',
                'title' => 'Configurations',
                'content' => 'Configurations are a simplified way of organizing the settings for each context separately, and you can modify the values at run time or modify them permanently, if you wish'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/maintenance.html',
                'title' => 'Maintenance mode',
                'content' => 'Maintenance mode is a way to partially disable the execution of your application, so that you can make adjustments to controllers, views, events'
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/production/send-file.html',
                'title' => 'X-Accel-Redirect & X-Sendfile',
                'content' => '<code>X-Accel-Redirect</code> and <code>X-Sendfile</code> are powerful features, but they are not available in environments such as the <strong>Built-in Web Server</strong>. To address this, the framework provides a built-in simulator that enables the use of these headers without any configuration.',
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/console-commands.html',
                'title' => 'Console commands',
                'content' => 'It is possible to create your own command-line commands, with support for classes (similar to Controllers). In addition to ready-made optimization commands, enable/disable maintenance mode',
                'experimental' => true,
            ],

            [
                'link' => 'https://inphinit.github.io/en/docs/experimental/',
                'title' => 'Experimental resources',
                'content' => 'If you like to experiment, new experimental classes are also available (e.g., CSV/TSV Reader, ENV file parser, HTTP method override), which will likely become standards in the framework after testing and acceptance by you',
                'experimental' => true,
            ],
        ];

        View::render('home', [
            'items' => $items,
            'time' => null,
            'version' => $version ? $version : ''
        ], View::UNSAFE);
    }

    public function checkup()
    {
        $check = new Checkup();

        $errors = $check->getErrors();
        $warnings = $check->getWarnings();

        View::data('environment', App::config('environment'));

        View::render('checkup', [
            'errors' => self::codeTags($errors),
            'warnings' => self::codeTags($warnings),
            'build_date' => Checkup::getBuildDate(),
        ], View::UNSAFE);
    }

    private static function codeTag($message)
    {
        $message = htmlspecialchars($message);
        $message = preg_replace('#(^|\s)`([^`]+?)`([,.?!\s])#', '$1<code>$2</code>$3', $message);
        $message = preg_replace('#(^|\s)\*([^*]+?)\*([,.?!\s])#', '$1<em>$2</em>$3', $message);

        return $message;
    }

    private static function codeTags(array $messages)
    {
        foreach ($messages as &$message) {
            $message = self::codeTag($message);
        }

        return $messages;
    }
}
