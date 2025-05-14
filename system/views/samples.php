<?php
use Inphinit\Viewing\View;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Samples - Inphinit PHP framework</title>
    <?php View::render('header'); ?>
</head>
<body>
    <a class="skip" href="#main">Skip to main content</a>
    <main>
        <nav id="links">
            <?php View::render('menu'); ?>
        </nav>

        <header id="samples">
            <h1>Samples</h1>
        </header>

        <section id="items">
        <dl>
            <dt>Basic</dt>
            <dd><a href="../info">/info</a></dd>
            <dd><a href="../memory">/memory</a></dd>
        </dl>

        <dl>
            <dt>Resource controller</dt>
            <dd><a href="../resource/">/resource/</a></dd>
            <dd><a href="../resource/create">/resource/create</a></dd>
            <dd><a href="../resource/100/edit">/resource/&lt;id&gt;/edit</a></dd>
            <dd><a href="../resource/100">/resource/&lt;id&gt;</a></dd>
        </dl>

        <dl>
            <dt>Implicit controller</dt>
            <dd><a href="../treaty/">/treaty/</a></dd>
            <dd><a href="../treaty/foo-bar-baz">/treaty/foo-bar-baz</a></dd>
        </dl>

        <dl>
            <dt>Routes and scopes</dt>
            <dd><a href="../routes/secure/">/routes/secure/</a></dd>
            <dd><a href="../routes/nonsecure/">/routes/nonsecure/</a></dd>
            <dd><a href="../routes/foo/<foo>/<bar>">/routes/foo/&lt;foo&gt;/&lt;bar&gt;</a></dd>
            <dd><a href="../routes/&lt;foo&gt;-&lt;bar&gt;">/routes/&lt;foo&gt;-&lt;bar&gt;</a></dd>
            <dd><a href="../routes/article/&lt;name&gt;/&lt;id>">/routes/article/&lt;name&gt;/&lt;id></a></dd>
            <dd><a href="../routes/blog/&lt;name&gt;-&lt;id:num&gt;">/routes/blog/&lt;name&gt;-&lt;id:num&gt;</a></dd>
            <dd><a href="../routes/test/&lt;id:num&gt;">/routes/test/&lt;id:num&gt;</a></dd>
            <dd><a href="../routes/test/foo/&lt;name:alpha>">/routes/test/foo/&lt;name:alpha></a></dd>
            <dd><a href="../routes/test/bar/&lt;barcode:alnum&gt;">/routes/test/bar/&lt;barcode:alnum&gt;</a></dd>
            <dd><a href="../routes/decimal/&lt;value:decimal&gt;">/routes/decimal/&lt;value:decimal&gt;</a></dd>
            <dd><a href="../routes/uuid/&lt;value:uuid>">/routes/uuid/&lt;value:uuid></a></dd>
            <dd><a href="../routes/version/&lt;value:version&gt;">/routes/version/&lt;value:version&gt;</a></dd>
            <dd><a href="../routes/nospace/&lt;value:nospace&gt;">/routes/nospace/&lt;value:nospace&gt;</a></dd>
            <dd><a href="../routes/custom/&lt;codeparam:customcode&gt;">/routes/custom/&lt;codeparam:customcode&gt;</a></dd>
        </dl>

        <dl>
            <dt>Debug</dt>
            <dd><a href="../debug/warning">/debug/warning</a></dd>
            <dd><a href="../debug/error">/debug/error</a></dd>
            <dd><a href="../debug/exception">/debug/exception</a></dd>
            <dd><a href="../debug/eval-error">/debug/eval-error</a></dd>
            <dd><a href="../debug/invalid/function/">/debug/invalid/function/</a></dd>
            <dd><a href="../debug/invalid/class-method/">/debug/invalid/class-method/</a></dd>
            <dd><a href="../debug/invalid/static-method/">/debug/invalid/static-method/</a></dd>
        </dl>

        <dl>
            <dt>DOM</dt>
            <dd><a href="../dom/array-to-html">/dom/array-to-html</a></dd>
            <dd><a href="../dom/array-to-xml">/dom/array-to-xml</a></dd>
            <dd><a href="../dom/to-array">/dom/to-array</a></dd>
            <dd><a href="../dom/css-selector">/dom/css-selector</a></dd>
            <dd><a href="../dom/file-error">/dom/file-error</a></dd>
        </dl>

        <dl>
            <dt>HTTP Negotiation</dt>
            <dd><a href="../http/negotiation/charset/all">/http/negotiation/charset/all</a></dd>
            <dd><a href="../http/negotiation/charset/high">/http/negotiation/charset/high</a></dd>
            <dd><a href="../http/negotiation/charset/low">/http/negotiation/charset/low</a></dd>
            <dd><a href="../http/negotiation/custom/all">/http/negotiation/custom/all</a></dd>
            <dd><a href="../http/negotiation/custom/high">/http/negotiation/custom/high</a></dd>
            <dd><a href="../http/negotiation/custom/low">/http/negotiation/custom/low</a></dd>
            <dd><a href="../http/negotiation/encoding/all">/http/negotiation/encoding/all</a></dd>
            <dd><a href="../http/negotiation/encoding/high">/http/negotiation/encoding/high</a></dd>
            <dd><a href="../http/negotiation/encoding/low">/http/negotiation/encoding/low</a></dd>
            <dd><a href="../http/negotiation/language/all">/http/negotiation/language/all</a></dd>
            <dd><a href="../http/negotiation/language/high">/http/negotiation/language/high</a></dd>
            <dd><a href="../http/negotiation/language/low">/http/negotiation/language/low</a></dd>
            <dd><a href="../http/negotiation/qfactor/all">/http/negotiation/qfactor/all</a></dd>
            <dd><a href="../http/negotiation/qfactor/high">/http/negotiation/qfactor/high</a></dd>
            <dd><a href="../http/negotiation/qfactor/low">/http/negotiation/qfactor/low</a></dd>
            <dd><a href="../http/negotiation/type/all">/http/negotiation/type/all</a></dd>
            <dd><a href="../http/negotiation/type/high">/http/negotiation/type/high</a></dd>
            <dd><a href="../http/negotiation/type/low">/http/negotiation/type/low</a></dd>
        </dl>

        <dl>
            <dt>Others</dt>
            <dd><a href="../http/headers">/http/headers</a></dd>
            <dd><a href="../http/download">/http/download</a></dd>
            <dd><a href="../samples/event">/samples/event</a></dd>
            <dd><a href="../samples/file">/samples/file</a></dd>
            <dd><a href="../samples/filesize?mode=com">/samples/filesize</a></dd>
            <dd><a href="../samples/session">/samples/session</a></dd>
            <dd><a href="../utilities/arrays">/utilities/arrays</a></dd>
            <dd><a href="../utilities/strings">/utilities/strings</a></dd>
        </dl>

        <dl>
            <dt>Extras</dt>
            <dd><a href="../maintenance/up">/maintenance/up</a></dd>
            <dd><a href="../maintenance/down">/maintenance/down</a></dd>
        </dl>
        </section>
    </main>
</body>
</html>
