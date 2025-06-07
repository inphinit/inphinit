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
            <dd><a href="../samples/info">/info</a></dd>
            <dd><a href="../samples/memory">/memory</a></dd>
        </dl>

        <dl>
            <dt>Resource controller</dt>
            <dd><a href="../samples/routes/resource/">/routes/resource/</a></dd>
            <dd><a href="../samples/routes/resource/create">/routes/resource/create</a></dd>
            <dd><a href="../samples/routes/resource/100/edit">/routes/resource/&lt;id&gt;/edit</a></dd>
            <dd><a href="../samples/routes/resource/100">/routes/resource/&lt;id&gt;</a></dd>
        </dl>

        <dl>
            <dt>Implicit controller</dt>
            <dd><a href="../samples/routes/treaty/">/routes/treaty/</a></dd>
            <dd><a href="../samples/routes/treaty/foo-bar-baz">/routes/treaty/foo-bar-baz</a></dd>
        </dl>

        <dl>
            <dt>Routes and scopes</dt>
            <dd><a href="../samples/routes/secure/">/routes/secure/</a></dd>
            <dd><a href="../samples/routes/nonsecure/">/routes/nonsecure/</a></dd>
            <dd><a href="../samples/routes/foo/hello-world">/routes/foo/{:[^/]+:}-{:[^/]+:}</a></dd>
            <dd><a href="../samples/routes/article/my-article/100">/routes/article/{:[^/]+:}/{:\d+:}</a></dd>
            <dd><a href="../samples/routes/blog/how-create-routes-200">/routes/blog/{:[^/]+:}-{:\d+:}</a></dd>
            <dd><a href="../samples/routes/test/2025">/routes/test/{:\d+:}</a></dd>
            <dd><a href="../samples/routes/test/foo/john">/routes/test/foo/{:[a-zA-Z]+:}</a></dd>
            <dd><a href="../samples/routes/test/bar/f0f0f0">/routes/test/bar/{:[\da-zA-Z]+:}</a></dd>
            <dd><a href="../samples/routes/decimal/0.9">/routes/decimal/{:(\d|[1-9]\d+)\.\d+:}</a></dd>
            <dd><a href="../samples/routes/dynamic-scope-foobar/route">/routes/dynamic-scope-{:[a-zA-Z]+:}/route</a></dd>
        </dl>

        <dl>
            <dt>Debug</dt>
            <dd><a href="../samples/debug/warning">/debug/warning</a></dd>
            <dd><a href="../samples/debug/error">/debug/error</a></dd>
            <dd><a href="../samples/debug/exception">/debug/exception</a></dd>
            <dd><a href="../samples/debug/eval-error">/debug/eval-error</a></dd>
            <dd><a href="../samples/debug/trigger-error">/debug/trigger-error</a></dd>
        </dl>

        <dl>
            <dt>DOM</dt>
            <dd><a href="../samples/dom/array-to-html">/dom/array-to-html</a></dd>
            <dd><a href="../samples/dom/array-to-xml">/dom/array-to-xml</a></dd>
            <dd><a href="../samples/dom/to-array">/dom/to-array</a></dd>
            <dd><a href="../samples/dom/css-selector">/dom/css-selector</a></dd>
            <dd><a href="../samples/dom/file-error">/dom/file-error</a></dd>
        </dl>

        <dl>
            <dt>HTTP</dt>
            <dd><a href="../samples/http/cache">/http/cache</a></dd>
            <dd><a href="../samples/http/download">/http/download</a></dd>
            <dd><a href="../samples/http/negotiation">/http/negotiation</a></dd>
            <dd><a href="../samples/http/negotiation/string">/http/negotiation/string</a></dd>
            <dd><a href="../samples/http/negotiation/qfactor">/http/negotiation/qfactor</a></dd>
            <dd><a href="../samples/http/get?foo[bar][baz]=hi!&amp;foo[list][0]=hello&amp;foo[list][1]=world">/http/get</a></dd>
        </dl>

        <dl>
            <dt>Others</dt>
            <dd><a href="../samples/event">/event</a></dd>
            <dd><a href="../samples/file">/file</a></dd>
            <dd><a href="../samples/utilities/arrays">/utilities/arrays</a></dd>
            <dd><a href="../samples/utilities/strings">/utilities/strings</a></dd>
            <dd><a href="../samples/utilities/version">/utilities/version</a></dd>
            <dd><a href="../samples/utilities/url">/utilities/url</a></dd>
            <dd><a href="../samples/session">/session</a></dd>
            <dd><a href="../samples/session/reset">/session/reset</a></dd>
            <dd><a href="../samples/session/regenerate">/session/regenerate</a></dd>
        </dl>

        <dl>
            <dt>Extras</dt>
            <dd><a href="../samples/maintenance/up">/maintenance/up</a></dd>
            <dd><a href="../samples/maintenance/down">/maintenance/down</a></dd>
        </dl>
        </section>
    </main>
</body>
</html>
