<?php
$bl = php_sapi_name() === 'cli' ? PHP_EOL : '<br>';

$system = rtrim(dirname(__FILE__), '/') . '/system/';
$systemData = $system . '/storage';

if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
    echo 'Ok: Your current version of PHP is ', PHP_VERSION, $bl;
} else {
    echo 'Fail: Inphinit requires PHP5.3 or major, your current version of PHP is ',
            PHP_VERSION, $bl;
}

if (function_exists('get_magic_quotes_gpc') === false || get_magic_quotes_gpc() === false) {
    echo 'Ok: magic_quotes_gpc is disabled', $bl;
} else {
    echo 'Fail: magic_quotes_gpc is enabled', $bl;
}

if (is_writable($systemData)) {
    echo 'Ok: Folder ./system/storage/ is writable', $bl;
} else {
    echo $systemData, $bl;
    echo 'Fail: Folder ./system/storage/ requires write permissions, use chmode', $bl;
}

if (function_exists('mb_detect_encoding')) {
    echo 'Ok: Experimental\Uri class and Inphinit\Helper::toAscii work, ',
            '"Multibyte String" is enabled in php', $bl;
} else {
    echo 'Fail: (Optinal) Inphinit\Experimental\Uri class and Inphinit\Helper::toAscii ',
            'not work, "Multibyte String" is disabled',
            ' in php (if needed for you)', $bl;
}

if (function_exists('iconv')) {
    echo 'Ok: Experimental\Uri work, "iconv" is ',
            'enabled in php', $bl;
} else {
    echo 'Fail: (Optinal) Inphinit\Experimental\Uri not work, ',
            '"iconv" is disabled in php (if needed for you)', $bl;
}

if (function_exists('finfo_file')) {
    echo 'Ok: Class Inphinit\Files (mimeType method) work, "finfo" is ',
            'enabled in php', $bl;
} else {
    echo 'Fail: (Optinal) Class Inphinit\Files (mimeType method) not work, ',
            '"finfo" is disabled in php (if needed for you)', $bl;
}

$systemConfigs = require $system . 'application/Config/config.php';

if ($systemConfigs['developer'] === false) {
    if (extension_loaded('xdebug')) {
        echo 'Warn: (Recomended) xdebug is enabled, is recomended disable this in "production mode"', $bl;
    }

    if (extension_loaded('xhprof')) {
        echo 'Warn: (Recomended) xhprof is enabled, is recomended disable this in "production mode"', $bl;
    }
}
