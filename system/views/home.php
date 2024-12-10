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
                <h1>Inphinit</h1>
                <?php if ($version): ?>
                <h2>Version <?=$version?></h2>
                <?php elseif ($time): ?>
                <h2><?=$time?></h2>
                <?php endif; ?>
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
