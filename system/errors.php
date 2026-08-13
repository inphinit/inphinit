<?php
use Inphinit\App;
use Inphinit\Http\Request;
use Inphinit\Http\Status;
use Inphinit\Viewing\View;

/** @var int $code */

if ($code === 503) {
    echo 'This site is currently down for maintenance and should be back soon!';
} else {
    View::data('environment', App::config('environment'));

    View::render('httperror', [
        'code' => $code,
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => Request::path(),
        'route' => INPHINIT_PATH,
        'title' => Status::message($code, 'Unknown Error')
    ]);
}
