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

$check = new Inphinit\Checkup();
$errors = $check->getErrors();
$warnings = $check->getWarnings();

function code_tag($message) {
    $message = preg_replace('#(^|\s)`([^`]+?)`([,.?!\s])#', '$1<code>$2</code>$3', $message);
    $message = preg_replace('#(^|\s)\*([^*]+?)\*([,.?!\s])#', '$1<em>$2</em>$3', $message);
    return $message;
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Checkup application</title>
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

    code {
        background: rgba(24,25,27,.72);
        padding: .2rem .4rem;
        margin: .05rem .2rem;
        display: inline-block;
        color: #fff;
        border-radius: .2rem;
        white-space: nowrap;
    }
    </style>
</head>
<body>
    <article id="others">
        <header>
            <h1>Checkup application</h1>

            <?php if ($errors): ?>
            <ul class="fail">
                <?php foreach ($errors as $error): ?>
                <li><strong>Fail:</strong> <?=code_tag($error)?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <?php if ($warnings): ?>
            <ul class="warn">
                <?php foreach ($warnings as $warn): ?>
                <li><strong>Recommended:</strong> <?=code_tag($warn)?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>

            <?php if (empty($errors) && empty($warnings)): ?>
            <div class="done">Your server is fine! 🖖👽</div>
            <?php endif; ?>
        </header>
    </article>
</body>
</html>
