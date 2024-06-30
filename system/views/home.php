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
        <header id="links">
            <?php if (INPHINIT_PATH === '/'): ?>
            <a href="./examples/">Examples</a>
            <?php endif; ?>

            <?php View::render('menu'); ?>
        </header>
        <section id="intro">
            <header>
                <h1>
                    Inphinit 2.0
                </h1>
                <h2><?=$intro?></h2>
            </header>
        </section>

        <?php if (isset($items[0])): ?>
        <section id="items">
            <?php foreach($items as $item): ?>
            <a rel="nofollow noopener noreferrer" target="_blank" href="<?=$item['link']?>">
                <h3><?=$item['title']?></h3>
                <p><?=$item['content']?></p>
            </a>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>
    </main>
</body>
</html>
