<div class="debug-inphinit">
<h3>Classes</h3>
<ul>
<?php foreach ($classes as $current): ?>
<li><?=$current?></li>
<?php endforeach; ?>
</ul>

<h3>Functions</h3>
<ul>
<?php foreach ($functions as $current): ?>
<li><?=$current?></li>
<?php endforeach; ?>
</ul>

<h3>Constants</h3>
<ul>
<?php foreach ($constants as $name => $current): ?>
<li><?=$name?> (<?=var_export($current)?>)</li>
<?php endforeach; ?>
</ul>

</div>
