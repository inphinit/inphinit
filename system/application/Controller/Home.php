<?php
namespace Controller;

use Inphinit\Packages;
use Inphinit\Viewing\View;

class Home
{
    public function index()
    {
        $version = Packages::version('inphinit/framework');

        View::render('home', [
            'intro' => 'Hello world!',
            'version' => $version ? $version : '',
            'time' => null
        ]);
    }
}
