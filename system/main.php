<?php
use Inphinit\Routing\Route;

Route::set('ANY', '/', 'Home:index');
Route::set('GET', '/users/{:\d+:}/{:\w+:}', 'Users\ProfileController:main');
