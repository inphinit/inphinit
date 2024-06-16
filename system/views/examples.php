<?php
use Inphinit\Viewing\View;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inphinit PHP framework</title>
    <?php View::render('header'); ?>
</head>
<body>
    <a class="skip" href="#main">Skip to main content</a>
    <main>
        <nav id="links">
            <?php View::render('menu'); ?>
        </nav>

        <header id="examples">
            <h1>Examples</h1>
        </header>

        <section id="items">
        <dl>
            <dt>Basic</dt>
            <dd><a href="../">/</a></dd>
            <dd><a href="../info">/info</a></dd>
            <dd><a href="../memory">/memory</a></dd>
            <dd><a href="../error">/error</a></dd>
            <dd><a href="../exception">/exception</a></dd>
            <dd><a href="../eval-error">/eval-error</a></dd>
            <dd><a href="../maintenance/up">/maintenance/up</a></dd>
            <dd><a href="../maintenance/down">/maintenance/down</a></dd>

            <dd><a href="../secure/">/secure/</a></dd>
            <dd><a href="../nonsecure/">/nonsecure/</a></dd>
        </dl>

        <dl>
            <dt>Resource controller</dt>
            <dd><a href="../resource/">/resource/</a></dd>
            <dd><a href="../resource/create">/resource/create</a></dd>
            <dd><a href="../resource/100/edit">/resource/&lt;id&gt;/edit</a></dd>
            <dd><a href="../resource/100">/resource/&lt;id&gt;</a></dd>
        </dl>
        <!--
        POST, /resource/
        PUT, /resource/<id>
        DELETE, /resource/<id>
        -->

        <dl>
            <dt>Implicit controller</dt>
            <dd><a href="../treaty/">/treaty/</a></dd>
            <dd><a href="../treaty/foo-bar-baz">/treaty/foo-bar-baz</a></dd>
        </dl>

        <dl>
            <dt>DOM</dt>
            <dd><a href="../samples/dom/css-selector">/samples/dom/css-selector</a></dd>
            <dd><a href="../samples/dom/to-array">/samples/dom/to-array</a></dd>
            <dd><a href="../samples/dom/from-array">/samples/dom/from-array</a></dd>
        </dl>

        <dl>
            <dt>Negotiation</dt>
            <dd><a href="../samples/negotiation/charset/all">/samples/negotiation/charset/all</a></dd>
            <dd><a href="../samples/negotiation/charset/high">/samples/negotiation/charset/high</a></dd>
            <dd><a href="../samples/negotiation/charset/low">/samples/negotiation/charset/low</a></dd>
            <dd><a href="../samples/negotiation/custom/all">/samples/negotiation/custom/all</a></dd>
            <dd><a href="../samples/negotiation/custom/high">/samples/negotiation/custom/high</a></dd>
            <dd><a href="../samples/negotiation/custom/low">/samples/negotiation/custom/low</a></dd>
            <dd><a href="../samples/negotiation/encoding/all">/samples/negotiation/encoding/all</a></dd>
            <dd><a href="../samples/negotiation/encoding/high">/samples/negotiation/encoding/high</a></dd>
            <dd><a href="../samples/negotiation/encoding/low">/samples/negotiation/encoding/low</a></dd>
            <dd><a href="../samples/negotiation/language/all">/samples/negotiation/language/all</a></dd>
            <dd><a href="../samples/negotiation/language/high">/samples/negotiation/language/high</a></dd>
            <dd><a href="../samples/negotiation/language/low">/samples/negotiation/language/low</a></dd>
            <dd><a href="../samples/negotiation/qfactor/all">/samples/negotiation/qfactor/all</a></dd>
            <dd><a href="../samples/negotiation/qfactor/high">/samples/negotiation/qfactor/high</a></dd>
            <dd><a href="../samples/negotiation/qfactor/low">/samples/negotiation/qfactor/low</a></dd>
            <dd><a href="../samples/negotiation/type/all">/samples/negotiation/type/all</a></dd>
            <dd><a href="../samples/negotiation/type/high">/samples/negotiation/type/high</a></dd>
            <dd><a href="../samples/negotiation/type/low">/samples/negotiation/type/low</a></dd>
        </dl>

        <dl>
            <dt>Others</dt>
            <dd><a href="../samples/event">/samples/event</a></dd>
            <dd><a href="../samples/http/headers">/samples/http/headers</a></dd>
            <dd><a href="../samples/http/download">/samples/http/download</a></dd>
            <dd><a href="../samples/file">/samples/file</a></dd>
            <dd><a href="../utilities/arrays">/utilities/arrays</a></dd>
            <dd><a href="../utilities/strings">/utilities/strings</a></dd>

            <dd><a href="../invalid/function/">/invalid/function/</a></dd>
            <dd><a href="../invalid/class-method/">/invalid/class-method/</a></dd>
            <dd><a href="../invalid/static-method/">/invalid/static-method/</a></dd>
        </dl>
        </section>
    </main>
</body>
</html>
