<?php

$app->action('ANY', '/', 'Home::index');
$app->action('GET', '/users/<id:num>/<user:alnum>', 'Users\Profile::view');
