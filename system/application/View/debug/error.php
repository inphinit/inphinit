<div class="code-inphinit">
<div class="code-inphinit-header">
    <?php echo $file; ?> on line <?php echo $line; ?>
</div>
<h3 class="error"><?php echo $message; ?></h3>
<?php
$data = $source['preview'];
$breakpoint = $source['breakpoint'];
$lines = count($data);
?>
<pre style="counter-reset: line <?php echo $line - $breakpoint - 1; ?>"><?php
    for ($i = 0; $i < $lines; $i++) {
        if ($breakpoint === $i) {
            echo '<span class="hl-line">', htmlspecialchars($data[$i], ENT_QUOTES), '</span>', EOL;
        } else {
            echo '<span>', htmlspecialchars($data[$i], ENT_QUOTES), '</span>', EOL;
        }
    }
?></pre>
</div>
