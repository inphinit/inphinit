<?php
namespace Controller;

use Inphinit\View;

class Home
{
    public function index()
    {
        View::render('home', array(
            'intro' => 'Hello world!'
        ));
    }
}
