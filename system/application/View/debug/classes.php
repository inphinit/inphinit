<div class="debug-inphinit">
<h3>Declared classes</h3>
<?php foreach ($classes as $className => $properties): ?>
    <h4><?php echo $className; ?></h4>

    <?php foreach ($properties as $name => $value): ?>
    <ul>
        <li>
            <strong>$<?php echo $name; ?>:</strong>
            <pre class="box"><?php var_dump($value); ?></pre>
        </li>
    </ul>
    <?php endforeach; ?>
    <hr>
<?php endforeach; ?>
</div>
