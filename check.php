<?php
define('INPHINIT_ROOT', strtr(__DIR__, '\\', '/') . '/');
define('INPHINIT_PATH', INPHINIT_ROOT . 'system/');

$response = require INPHINIT_PATH . 'vendor/inphinit/framework/src/requirements.php';

if (PHP_SAPI === 'cli') {
    if (empty($response->error) === false) {
        echo ' - Fail: ' . implode(PHP_EOL . ' - Fail: ', $response->error), PHP_EOL;
    }

    if (empty($response->warn) === false) {
        echo ' - Recommended: ' . implode(PHP_EOL . ' - Recommended: ', $response->warn), PHP_EOL;
    }

    if (empty($response->error) && empty($response->warn)) {
        echo 'Your server is fine! ;]', PHP_EOL;
    }
} else {
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inphinit php framework</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100" rel="stylesheet" type="text/css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
    <style type="text/css">
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
    }
    body {
        background: #F7F6F6;
        min-width: 210px;
    }
    .container {
        height: 100%;
    }
    .container h1 {
        color: #5F5656;
        font-size: 48pt;
        font-weight: 100;
        padding: 15px 0 20px 0;
        margin: 0;
        text-align: center;
    }
    .container h1, .done {
        font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, freesans, sans-serif;
    }
    .container ul {
        list-style-type: none;
        padding: 0 5px;
        margin: 0;
    }
    .container ul li {
        border-radius: 4px;
        margin: 0 0 5px 0;
        padding: 10px;
        color: #fff;
    }
    .container ul.fail li {
        background-color: #cc0a0a;
    }
    .container ul.warn li {
        background-color: #e69e1b;
    }

    .done {
        font-size: 24pt;
        padding: 12px 0;
        text-align: center;
    }

    @media only screen and (max-width: 400px) {
        .container h1 {
            font-size: 36pt;
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Check server requirements</h1>

        <?php
        if ($response->error) {
            echo '<ul class="fail"><li>Fail: ',
                implode('</li><li>Fail: ', $response->error),
                '</li></ul>';
        }

        if ($response->warn) {
            echo '<ul class="warn"><li>Recommended: ',
                implode('</li><li>Recommended: ', $response->warn),
                '</li></ul>';
        }

        if (empty($response->error) && empty($response->warn)) {
            echo '<div class="done">Your server is fine! :)</div>';
        }
        ?>
    </div>
</body>
</html>
<?php
}
