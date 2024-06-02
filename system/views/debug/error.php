<?php
use Inphinit\Debugging\Debug;
?>
<div class="code-inphinit">
<div class="code-inphinit-header"><?=Debug::editor($file, $line)?></div>
<div class="code-inphinit-error">
<?=nl2br(Debug::searcher($message))?>
</div>

<?php if ($source): ?>

<?php
$data = $source['preview'];
$lines = count($data);
?>

<?php if ($lines): ?>

<?php
$breakpoint = $source['breakpoint'];
$start = $line - $breakpoint;
$start = $start < 1 ? 0 : $start;
$breakpoint--;
?>

<pre style="counter-reset: line <?=$start?>"><?php
for ($i = 0; $i < $lines; $i++) {
    $current = trim($data[$i], "\r\n");
    $current = htmlspecialchars($current, ENT_COMPAT);

    if ($breakpoint === $i) {
        echo "<span class=\"hl-line\">{$current}</span>\n";
    } else {
        echo "<span>{$current}</span>\n";
    }
}
?></pre>
<?php endif; ?>

<?php endif; ?>
</div>
