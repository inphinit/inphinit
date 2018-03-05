<div class="code-inphinit">
<div class="code-inphinit-header"><?php echo $file; ?> on line <?php echo $line; ?></div>
<div class="error">
<?php echo nl2br(Inphinit\Experimental\Debug::searcherror($message)); ?>
</div>
<?php
$data = $source['preview'];
$breakpoint = $source['breakpoint'];
$lines = count($data);
$start = $line - $breakpoint;
$start = $start < 1 ? 0 : $start;
$breakpoint--;
?>

<?php if ($lines): ?>
<pre style="counter-reset: line <?php echo $start; ?>"><?php
for ($i = 0; $i < $lines; $i++) {
    $current = trim($data[$i], "\r\n");
    $current = htmlspecialchars($current, ENT_QUOTES);

    if ($breakpoint === $i) {
        echo '<span class="hl-line">', $current, '</span>', EOL;
    } else {
        echo '<span>', $current, '</span>', EOL;
    }
}
?></pre>
<?php endif; ?>

</div>
