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
            <dd><a href="../samples/routes">/routes/routes (list routes)</a></dd>
            <dd><a href="../samples/views">/views</a></dd>
            <dd><a href="../checkup">/checkup</a></dd>
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
            <dd><a href="../samples/routes/foo/hello-world">/routes/foo/&lt;foo&gt;-&lt;bar&gt;</a></dd>
            <dd><a href="../samples/routes/article/my-article/100">/routes/article/&lt;name&gt;/&lt;id&gt;</a></dd>
            <dd><a href="../samples/routes/blog/how-create-routes-200">/routes/blog/&lt;name&gt;-&lt;id:num&gt;</a></dd>
            <dd><a href="../samples/routes/test/2025">/routes/test/&lt;id:num&gt;</a></dd>
            <dd><a href="../samples/routes/test/foo/john">/routes/test/foo/&lt;name:alpha&gt;</a></dd>
            <dd><a href="../samples/routes/test/bar/f0f0f0">/routes/test/bar/&lt;barcode:alnum&gt;</a></dd>
            <dd><a href="../samples/routes/decimal/0.9">/routes/decimal/&lt;value:decimal&gt;</a></dd>
            <dd><a href="../samples/routes/uuid/425e08ec-8e18-4d2c-b667-10b0306679c2">/routes/uuid/&lt;value:uuid&gt;</a></dd>
            <dd><a href="../samples/routes/version/1.0.1">/routes/version/&lt;value:version&gt;</a></dd>
            <dd><a href="../samples/routes/nospace/AnythingFooBarBaz">/routes/nospace/&lt;value:nospace&gt;</a></dd>
            <dd><a href="../samples/routes/custom/A0001">/routes/custom/&lt;codeparam:customcode&gt;</a></dd>
            <dd><a href="../samples/routes/dynamic-scope-foobar/route">/routes/dynamic-scope-&lt;name:alpha&gt;/route</a></dd>
        </dl>

        <dl>
            <dt>Debug</dt>
            <dd><a href="../samples/debug/warning">/debug/warning</a></dd>
            <dd><a href="../samples/debug/error">/debug/error</a></dd>
            <dd><a href="../samples/debug/exception">/debug/exception</a></dd>
            <dd><a href="../samples/debug/eval-error">/debug/eval-error</a></dd>
            <dd><a href="../samples/debug/trigger-error">/debug/trigger-error</a></dd>
            <dd><a href="../samples/debug/invalid/function/">/debug/invalid/function/</a></dd>
            <dd><a href="../samples/debug/invalid/class-method/">/debug/invalid/class-method/</a></dd>
            <dd><a href="../samples/debug/invalid/static-method/">/debug/invalid/static-method/</a></dd>
            <dd><a href="../samples/routes/error/http-method/">/routes/error/http-method/</a></dd>
            <dd><a href="../samples/routes/error/duplicate-methods/">/routes/error/duplicate-methods/</a></dd>
            <dd><a href="../samples/routes/error/invalid-controller/">/routes/error/invalid-controller/</a></dd>
            <dd><a href="../samples/routes/error/__construct/">/routes/error/__construct/</a></dd>
            <dd><a href="../samples/routes/error/set-namespace/">/routes/error/set-namespace/</a></dd>
            <dd><a href="../samples/routes/error/set-pattern/">/routes/error/set-pattern/</a></dd>
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
            <dd><a href="../samples/http/is">/http/is</a></dd>
            <dd><a href="../samples/http/negotiation">/http/negotiation</a></dd>
            <dd><a href="../samples/http/negotiation/string">/http/negotiation/string</a></dd>
            <dd><a href="../samples/http/negotiation/qfactor">/http/negotiation/qfactor</a></dd>
            <dd><a href="../samples/http/get?foo[bar][baz]=hi!&amp;foo[list][0]=hello&amp;foo[list][1]=world">/http/get</a></dd>
            <dd><a href="../samples/http/methods?_method=DELETE">/http/methods?_method=DELETE (Experimental)</a></dd>
            <dd><a href="../samples/http/methods?_HttpMethod=patch">/http/methods?_HttpMethod=patch (Experimental)</a></dd>
        </dl>

        <dl>
            <dt>Session</dt>
            <dd><a href="../samples/session">/session</a></dd>
            <dd><a href="../samples/session/reset">/session/reset</a></dd>
            <dd><a href="../samples/session/regenerate">/session/regenerate</a></dd>
        </dl>

        <dl>
            <dt>Others</dt>
            <dd><a href="../samples/event">/event</a></dd>
            <dd><a href="../samples/file">/file</a></dd>
            <dd><a href="../samples/filesize">/filesize</a></dd>
            <dd><a href="../samples/utilities/arrays">/utilities/arrays</a></dd>
            <dd><a href="../samples/utilities/strings">/utilities/strings</a></dd>
            <dd><a href="../samples/utilities/version">/utilities/version</a></dd>
            <dd><a href="../samples/utilities/url">/utilities/url</a></dd>
            <dd><a href="../samples/packages">/samples/packages</a></dd>
        </dl>

        <dl>
            <dt>Sendfile</dt>
            <dd><a href="../samples/sendfile/header">/samples/sendfile/header</a></dd>
            <dd><a href="../samples/sendfile/x-accel-redirect">/samples/sendfile/&lt;mode&gt; (X-Accel-Redirect)</a></dd>
            <dd><a href="../samples/sendfile/x-sendfile">/samples/sendfile/&lt;mode&gt; (X-Sendfile)</a></dd>
            <dd><a href="../samples/sendfile/fallback">/samples/sendfile/&lt;mode&gt; (fallback)</a></dd>
            <dd><a href="../samples/sendfile/alternate">/samples/sendfile/&lt;mode&gt; (X-Accel-Redirect or X-Sendfile)</a></dd>
        </dl>

        <dl>
            <dt>CSV (Experimental)</dt>
            <dd><a href="../samples/csv/">/samples/csv/</a></dd>
            <dd><a href="../samples/csv/convert">/samples/csv/convert</a></dd>
            <dd><a href="../samples/csv/index.json">/samples/csv/index.json</a></dd>
            <dd><a href="../samples/csv/pairs.json">/samples/csv/pairs.json</a></dd>
            <dd><a href="../samples/csv/output">/samples/csv/output</a></dd>
            <dd><a href="../samples/csv/tsv">/samples/csv/tsv</a></dd>

            <dt>TSV (Experimental)</dt>
            <dd><a href="../samples/tsv/">/samples/tsv/</a></dd>
            <dd><a href="../samples/tsv/convert">/samples/tsv/convert</a></dd>
            <dd><a href="../samples/tsv/index.json">/samples/tsv/index.json</a></dd>
            <dd><a href="../samples/tsv/pairs.json">/samples/tsv/pairs.json</a></dd>
            <dd><a href="../samples/tsv/output">/samples/tsv/output</a></dd>
            <dd><a href="../samples/tsv/csv">/samples/tsv/csv</a></dd>
        </dl>

        <dl>
            <dt>Maintenance</dt>
            <dd><a href="../samples/maintenance/on">/maintenance/on</a></dd>
            <dd><a href="../samples/maintenance/off">/maintenance/off</a></dd>
            <dt>Commands</dt>
            <dd><a href="../samples/commands/run">/commands/run (equiv. <code>run hello --name &lt;name&gt;</code>)</a></dd>
            <dd><a href="../samples/commands/unknown">/commands/unknown (equiv. <code>run unknown</code>)</a></dd>
        </dl>
        </section>
    </main>
</body>
</html>
