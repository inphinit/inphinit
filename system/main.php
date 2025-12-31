<?php

use Inphinit\Http\Response;

/** @var Inphinit\App $app */

$app->action('ANY', '/', 'HomeController::index');
$app->action('GET', '/checkup', 'HomeController::checkup');
$app->action('GET', '/users/<id:num>/<user:alnum>', 'Users\ProfileController::view');
