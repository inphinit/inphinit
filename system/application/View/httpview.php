<?php
use Inphinit\Viewing\View;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inphinit PHP framework</title>
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
        <article id="main">
            <header>
                <h1>
                    <strong><?php echo $status; ?></strong>
                    <?php echo $title; ?>
                </h1>

                <p>
                    Route <code><?php echo $route; ?></code>
                    <?php if ($route !== $path): ?>
                    (fullpath: <code><?php echo $path; ?></code>)
                    <?php endif; ?>
                </p>
            </header>
        </article>
        <?php View::render('footer'); ?>
    </main>
</body>
</html>
