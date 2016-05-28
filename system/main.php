<?php
use Inphinit\App;
use Inphinit\View;
use Inphinit\Request;
use Inphinit\Routing\Route;

App::on('changestatus', function ($status, $msg) {
    if ($status === 503) {
        echo 'This site is currently down for maintenance and should be back soon!';
    } elseif (in_array($status, array(401, 403, 404, 500, 501))) {
        View::forceRender();
        View::render('httpview', array(
            'title'  => $msg ? $msg : 'Página inacessível',
            'method' => $_SERVER['REQUEST_METHOD'],
            'path'   => Request::path(),
            'route'  => Request::path(true),
            'status' => $status
        ));
        exit;
    }
});

Route::set('ANY', '/', 'Home:index');
Route::set('ANY', 're:#^/user/([a-z0-9_]+)$#', 'Users.Profile:view');

// Navitate to http://[server]/info
Route::set('ANY', '/info', 'Examples:info');

// Navitate to http://[server]/print_r
Route::set('ANY', '/print_r', 'Examples:arrayArgs');

// Navitate to http://[server]/closure
Route::set('ANY', '/closure', 'Examples:arrayClosure');
