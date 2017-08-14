<?php
use Inphinit\App;
use Inphinit\Request;
use Inphinit\Viewing\View;
use Inphinit\Routing\Route;

Route::set('ANY', '/', 'Home:index');

// Navitate to http://[server]/user/[YOUR NAME] like: http://[server]/user/mary
Route::set('ANY', '/user/{:[a-z0-9_.\-]+:}', 'Users.Profile:view');

// Navitate to http://[server]/info
Route::set('ANY', '/info', 'Examples:info');

// Navitate to http://[server]/eventexample
Route::set('ANY', '/eventexample', 'Examples:eventready');

// Navigate to http://[server]/product/12345 or http://[server]/product/12345/
Route::set('GET', '/product/{:\d+:}{:/?:}', function ($id) {
    return 'Product ID: ' . $id;
});

App::on('changestatus', function ($status, $msg) {
    if ($status === 503) {
        echo 'This site is currently down for maintenance and should be back soon!';
    } elseif (in_array($status, array(401, 403, 404, 500, 501))) {
        if (App::state() < 3) {
            View::forceRender();
        }

        View::render('httpview', array(
            'title'  => $msg ? $msg : 'This page is not reachable',
            'method' => $_SERVER['REQUEST_METHOD'],
            'route'  => Request::path(true),
            'path'   => Request::path(),
            'status' => $status
        ));

        exit;
    }
});
