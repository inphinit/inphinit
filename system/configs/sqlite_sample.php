<?php
// A sample for SQLite (adjust as desired and edit the environment variables or the .env file).

use Inphinit\Experimental\Environment\Env;

return array(
    'encryption_key' => Env::entry('DB_PASS'),
    'host' => INPHINIT_SYSTEM . '/foo/bar.db',
    'mode' => SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE
);
