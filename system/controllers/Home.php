<?php
namespace Controller;

use Inphinit\Viewing\View;

class Home
{
    public function index()
    {
        $items = [
            [
                'link' => '#',
                'title' => 'Routing',
                'content' => 'Routing provides a means to map URL paths to controller actions or callable functions, and you can scope routes based on URL matching. Both routes and route scopes support patterns'
            ], [
                'link' => '#',
                'title' => 'Controllers',
                'content' => 'Controllers are a means of organizing logic, separating by namespaces and classes, they must be stored in the <code>system/controllers/</code> folder.'
            ], [
                'link' => '#',
                'title' => 'Resource routes',
                'content' => 'Resource routes are a way of defining a set of routes that map the various CRUD (Create, Read, Update, and Delete) operations to a controller with resources. By using resource routes, you can quickly define all the routes needed for your application in a single line of code'
            ], [
                'link' => '#',
                'title' => 'Implicity routes',
                'content' => 'Implicit controllers are a way to turn class methods into routes, based on the name, being able to organize the logic and routes at the same time in the controller itself'
            ], [
                'link' => '#',
                'title' => 'HTTP',
                'content' => 'Some helper functions are provided to work with HTTP requests and responses, including a mechanism for work with content negotiation (eg.: Accept, Accept-Encoding, TE headers)'
            ], [
                'link' => '#',
                'title' => 'Cache',
                'content' => 'In addition to caching instructions via HTTP, it is also possible to create a cache of dynamic pages, created by routes, and issue the 304 code when convenient, reducing the consumption of resources that do not need constant updating'
            ], [
                'link' => '#',
                'title' => 'DOM',
                'content' => 'The DOM (Document Object Model) is an API that represents and interacts with any HTML or XML-based markup language document. We provide a way to use CSS selectors in documents on the server and in debug mode you will be able to identify problems in the structure of a loaded document (XML or HTML)'
            ], [
                'link' => '#',
                'title' => 'Events',
                'content' => 'Events are a simple way to make calls at any time during the request execution time'
            ], [
                'link' => '#',
                'title' => 'Debugging',
                'content' => 'Debug mode is a way of detecting faults before going into production, directly on the web page, from which you will receive a detailed message, with a preview of the code snippet, indicating the line closest to the fault. You will also be able to predict route failures, route scopes and analyze problems in XML and HTML documents in detail'
            ], [
                'link' => '#',
                'title' => 'Configurations',
                'content' => 'Configurations are a simplified way of organizing the settings for each context separately, and you can modify the values at run time or modify them permanently, if you wish'
            ], [
                'link' => '#',
                'title' => 'Maintenance mode',
                'content' => 'Maintenance mode is a way to partially disable the execution of your application, so that you can make adjustments to controllers, views, events and vendor'
            ], [
                'link' => '#',
                'title' => 'Read more',
                'content' => '...'
            ]
        ];

        View::render('home', [
            'items' => $items,
            'intro' => 'PHP framework'
        ]);
    }
}
