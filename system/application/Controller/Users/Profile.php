<?php
namespace Controller\Users;

use Inphinit\View;

class Profile
{
    public function view($username)
    {
        View::render('foo.bar', array(
            'name' => $username
        ));
    }
}
