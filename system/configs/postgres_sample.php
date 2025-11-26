<?php
/*
A sample for PostgreSQL, adjust as desired and edit the environment variables or the .env file:

DB_HOST=host
DB_USER=user
DB_PASS=password
DB_DATABASE=database
*/

use Inphinit\Experimental\Environment\Env;

return array(
    'host' => Env::entry('DB_HOST'),
    'user' => Env::entry('DB_USER'),
    'pass' => Env::entry('DB_PASS'),
    'database' => Env::entry('DB_DATABASE'),
    'options'=> '--client_encoding=UTF8'
);
