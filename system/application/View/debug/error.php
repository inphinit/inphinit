<div class="box">
<h1>Error</h1>
<h2><?php echo $message, ' in ', $file , ':', $line; ?></h2>
<pre><?php
    $data = $source['preview'];
    $breakpoint = $source['breakpoint'];
    $lines = count($data);

    for ($i = 0; $i < $lines; $i++) {
        if ($breakpoint === $i) {
            echo '<strong style="color: red;">', htmlspecialchars($data[$i], ENT_QUOTES), '</strong>', EOL;
        } else {
            echo htmlspecialchars($data[$i], ENT_QUOTES), EOL;
        }
    }
?></pre>
</div>