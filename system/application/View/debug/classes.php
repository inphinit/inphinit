<div class="box">
<h1>Declared classes</h1>
<?php foreach ($classes as $className => $properties): ?>
    <h2><?php echo $className; ?></h2>

    <?php foreach ($properties as $name => $value): ?>
    <ul>
        <li>
            <strong>$<?php echo $name; ?>:</strong>
            <pre class="box"><?php var_dump($value); ?></pre>
        </li>
    </ul>
    <?php endforeach; ?>

<?php endforeach; ?>
</div>
