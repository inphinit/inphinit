<?php
namespace Controller\Users;

use Inphinit\Viewing\View;

class ProfileController
{
    public function main($id, $username)
    {
        View::render('foo.bar', [
            'id' => $id,
            'username' => $username
        ]);
    }
}
