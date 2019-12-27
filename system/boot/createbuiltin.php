<?php
/*
 * Usage with command line: php importpackages.php
 */

$serverPath = strtr(realpath(__DIR__ . '/../../'), '\\', '/');

require_once $serverPath . '/system/vendor/inphinit/framework/src/Setup.php';

SetupBuiltIn();
