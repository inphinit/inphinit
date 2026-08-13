<?php

/** @var Inphinit\App $app */

$app->action('ANY', '/', 'HomeController::index');
$app->action('GET', '/checkup', 'CheckupController::checkup');
$app->action('GET', '/users/<id:num>/<user:alnum>', 'Users\ProfileController::view');
