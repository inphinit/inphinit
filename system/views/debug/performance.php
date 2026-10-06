<?php
use Inphinit\Viewing\View;
?>
<div class="debug-inphinit">
<?php View::once('debug.style'); ?>
<h3>Performance</h3>
<ul>
    <li>Memory usage: <?=$usage?></li>
    <li>Memory peak: <?=$peak?></li>
    <li>Memory usage real: <?=$real?></li>
    <li>Time: <?=$time?></li>
</ul>
</div>
