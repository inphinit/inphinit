<?php
namespace Controller\Users;

use Inphinit\Viewing\View;

class ProfileController
{
    public function view($app, $params)
    {
        $id = $params['id'];
        $username = $params['user'];

        View::render('foo.bar', [
            'id' => $id,
            'username' => $username
        ]);
    }
}
