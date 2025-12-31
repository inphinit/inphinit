<?php if (INPHINIT_PATH === '/'): ?>
    <a href="./checkup">Checkup</a>

    <?php if ($environment === 'development'): ?>
    <a href="./samples/">Samples</a>
    <?php endif; ?>

<?php else: ?>
    <a href="<?=INPHINIT_URL?>/">Home</a>
<?php endif; ?>

<a href="https://inphinit.github.io/en/docs/"
    target="_blank" rel="nofollow noopener noreferrer">Documentation</a>
<a href="https://inphinit.github.io/api/2.1/"
    target="_blank" rel="nofollow noopener noreferrer">API</a>
<a href="https://twitter.com/inphinitphp"
    target="_blank" rel="nofollow noopener noreferrer">Twitter</a>
<a href="https://victory-css.github.io/"
    target="_blank" rel="nofollow noopener noreferrer">Victory.css</a>
<a href="https://github.com/inphinit/inphinit/"
    target="_blank" rel="nofollow noopener noreferrer">Others</a>
