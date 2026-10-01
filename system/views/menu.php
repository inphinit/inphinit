<?php if (INPHINIT_PATH === '/'): ?>
    <a href="./checkup">Checkup</a>

    <?php if ($environment === 'development'): ?>
    <a href="./samples/">Samples</a>
    <?php endif; ?>

<?php else: ?>
    <a href="<?=INPHINIT_BASE_URL?>/">Home</a>
<?php endif; ?>

<a href="https://twitter.com/inphinitphp"
    target="_blank" rel="nofollow noopener noreferrer">Twitter</a>
<a href="https://victory-css.github.io/"
    target="_blank" rel="nofollow noopener noreferrer">Victory.css</a>
<a href="https://github.com/inphinit/inphinit/"
    target="_blank" rel="nofollow noopener noreferrer">Repository</a>
