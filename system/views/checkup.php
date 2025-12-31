<?php
use Inphinit\Viewing\View;
?>
<!DOCTYPE html>
<html>
<head>
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
        color: #000;
    }

    .done {
        font-size: 1.8rem;
        padding: .1rem 0;
        text-align: center;
    }

    p {
        padding: .1rem 0;
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
    <a class="skip" href="#main">Skip to main content</a>
    <main id="others">
        <header id="links">
            <?php View::render('menu'); ?>
        </header>
        <section>
            <div>
                <h1>Checkup application</h1>

                <?php if ($errors): ?>
                <ul class="fail">
                    <?php foreach ($errors as $error): ?>
                    <li><strong>Fail:</strong> <?=$error?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if ($warnings): ?>
                <ul class="warn">
                    <?php foreach ($warnings as $warn): ?>
                    <li><strong>Recommended:</strong> <?=$warn?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if (empty($errors) && empty($warnings)): ?>
                <div class="done">Your server is fine! 🖖👽</div>
                <?php endif; ?>

                <?php if ($environment === 'development'): ?>
                <p>PHP <?=PHP_VERSION?> - Build date: <?=$build_date?></p>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
