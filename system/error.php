<?php
use Inphinit\App;
use Inphinit\Viewing\View;
use Inphinit\Http\Request;
use Inphinit\Http\Status;

if ($status === 503) {
    echo 'This site is currently down for maintenance and should be back soon!';
} elseif ($status >= 400 && $status < 600) {
    View::forceRender();

    View::render('httpview', array(
        'method' => $_SERVER['REQUEST_METHOD'],
        'status' => $status,
        'route' => Request::path(true),
        'path' => Request::path(),
        'title' => Status::message($status, 'Unknown Error')
    ));
}
