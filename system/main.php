<?php

$app->action('ANY', '/', 'HomeController::index');
$app->action('GET', '/users/<id:num>/<user:alnum>', 'Users\ProfileController::view');
