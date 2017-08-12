<?php
namespace Controller;

use Inphinit\Viewing\View;

class Home
{
    public function index()
    {
        View::render('home', array(
            'intro' => 'Hello world!'
        ));
    }
}
