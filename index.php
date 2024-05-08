<?php
// register_shutdown_function(function () {
//     echo '<br>memory peak usage: ', round(memory_get_peak_usage() / 1024 / 1024, 5), 'MB';

//     $objs = array();

//     foreach (get_declared_classes() as $value) {
//         $value = ltrim($value, '\\');
//         $cname = new \ReflectionClass($value);

//         if (false === $cname->isInternal()) {
//             $objs[$value] = $cname->getDefaultProperties();
//         }
//     }

//     echo '<pre>';
//     print_r($objs);
//     print_r(get_included_files());
//     echo '</pre>';
// });

define('INPHINIT_START', microtime(true));
define('INPHINIT_ROOT', strtr(__DIR__, '\\', '/') . '/');
define('INPHINIT_PATH', INPHINIT_ROOT . 'system/');
define('INPHINIT_COMPOSER', false);

require_once INPHINIT_PATH . 'boot/start.php';
