<?php
use Inphinit\Routing\Route;

Route::set('GET', '/', function () {
    return 'Inphinit v0.6';
});

// Route::set('ANY', '/', 'Home:index');

// // Navigate to http://[server]/user/[YOUR NAME] like: http://[server]/user/mary
// Route::set('ANY', '/user/{:[a-z0-9_.\-]+:}', 'Users.Profile:view');

// // Navigate to http://[server]/info
// Route::set('ANY', '/info', 'Examples:info');

// // Navigate to http://[server]/eventexample
// Route::set('ANY', '/eventexample', 'Examples:eventready');

// // Navigate to http://[server]/product/12345 or http://[server]/product/12345/
// Route::set('GET', '/product/{:\d+:}{:/?:}', function ($id) {
//     return 'Product ID: ' . $id;
// });

