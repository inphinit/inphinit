<?php

$app->action('ANY', '/', 'Home::index');
$app->action('ANY', '/users/<id:num>/<user:alphanum>', 'Users\Profile::view');
