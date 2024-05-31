<?php
namespace Controller\Users;

use Inphinit\Viewing\View;

class Profile
{
    public function view($username)
    {
        View::render('foo.bar', [
            'name' => $username
        ]);
    }
}
