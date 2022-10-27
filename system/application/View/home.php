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
        <article id="main">
            <header>
                <h1>Inphinit - PHP framework</h1>
                <h2><?php echo $intro; ?></h2>
            </header>
        </article>
        <?php View::render('footer'); ?>
    </main>
</body>
</html>
