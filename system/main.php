<?php

/** @var Inphinit\App $app */

$app->action('ANY', '/', 'WelcomeController::index');
$app->action('GET', '/checkup', 'CheckupController::checkup');
$app->action('GET', '/users/<id:num>/<user:alnum>', 'Samples\Users\ProfileController::view');
