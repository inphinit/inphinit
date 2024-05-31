<?php
use Inphinit\Viewing\View;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inphinit PHP framework</title>
    <?php View::render('header'); ?>
</head>
<body>
    <a class="skip" href="#main">Skip to main content</a>
    <main>
        <article id="intro">
            <header>
                <h1>Inphinit</h1>
                <h2><?php echo $intro; ?></h2>
            </header>
        </article>
        <footer id="links">
            <?php View::render('menu'); ?>
        </footer>
    </main>
</body>
</html>
