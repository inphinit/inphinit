<?php
use Inphinit\Viewing\View;

/*
 * Navigate to: http://[your website]/generate-htaccess.php
 */

if (!defined('INPHINIT_ROOT')) {
    define('INPHINIT_ROOT', str_replace('\\', '/', realpath(__DIR__ . '/..')));
    define('INPHINIT_SYSTEM', INPHINIT_ROOT . '/system');
    define('INPHINIT_COMPOSER', false);
}

require_once INPHINIT_SYSTEM . '/vendor/inphinit/framework/src/boot.php';

View::forceRender();

$check = new Inphinit\Checkup($app);
$errors = $check->getErrors();
$warnings = $check->getWarnings();

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>check up application</title>
    <?php View::render('header'); ?>
    <style>
    ul.fail, ul.warn {
        text-align: left;
        list-style-type: none;
        padding: 0 5px;
        margin: 0;
    }
    ul.fail li, ul.warn li {
        border-radius: 4px;
        margin: 0 0 5px 0;
        padding: 10px;
        color: #fff;
    }
    ul.fail li {
        background-color: #cc0a0a;
    }
    ul.warn li {
        background-color: #e69e1b;
    }

    .done {
        font-size: 1.8rem;
        padding: 0.5px 0;
        text-align: center;
    }
    </style>
</head>
<body>
    <article id="others">
        <header>
            <h1>
                Inphinit requirements
            </h1>

            <?php
            if ($errors) {
                echo '<ul class="fail"><li>Fail: ',
                implode('</li><li>Fail: ', $errors),
                '</li></ul>';
            }

if ($warnings) {
    echo '<ul class="warn"><li>Recommended: ',
    implode('</li><li>Recommended: ', $warnings),
    '</li></ul>';
}

if (empty($errors) && empty($warnings)) {
    echo '<div class="done">Your server is fine! 🖖👽</div>';
}
?>
        </header>
    </article>
</body>
</html>
