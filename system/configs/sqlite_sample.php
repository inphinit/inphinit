<?php
/*
A sample for SQLite, adjust as desired and edit the environment variables or the .env file:

DB_ENCRYPTION_KEY=key
*/

use Inphinit\Experimental\Environment\Env;

return array(
    'database' => INPHINIT_SYSTEM . '/foo/bar.db',
    'encryption_key' => Env::entry('DB_ENCRYPTION_KEY'),
    'mode' => SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
);
