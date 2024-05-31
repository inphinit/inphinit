<?php
use Inphinit\Viewing\View;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?=$status?> - <?=$title?></title>
    <?php View::render('header'); ?>
    <style type="text/css">
    h1 {
        font-size: 3rem;
    }

    h1 > strong {
        font-size: 150%;
    }
    </style>
</head>
<body>
    <a class="skip" href="#main">Skip to main content</a>
    <main>
        <article id="error">
            <header>
                <h1>
                    <strong><?=$status?></strong>
                    <?=$title?>
                </h1>

                <p>
                    Route <code><?=$route?></code>
                    <?php if ($route !== $path): ?>
                    (fullpath: <code><?=$path?></code>)
                    <?php endif; ?>
                </p>
            </header>
        </article>
        <footer id="links">
            <?php View::render('menu'); ?>
        </footer>
    </main>
</body>
</html>
