<?php
use Inphinit\App;
use Inphinit\Http\Request;
use Inphinit\Http\Status;
use Inphinit\Viewing\View;

if ($code === 503) {
    echo 'This site is currently down for maintenance and should be back soon!';
} else {
    View::render('httpview', [
        'method' => $_SERVER['REQUEST_METHOD'],
        'path' => Request::path(),
        'route' => INPHINIT_PATH,
        'code' => $code,
        'title' => Status::message($code, 'Unknown Error')
    ]);
}
