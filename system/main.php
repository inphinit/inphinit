<?php
use Inphinit\Routing\Route;

Route::set('ANY', '/', 'Home:index');

Route::set('GET', '/info', 'phpinfo');
