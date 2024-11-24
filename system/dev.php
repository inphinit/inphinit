<?php

use Inphinit\App;
use Inphinit\Routing\Route;
use Inphinit\Routing\Group;
use Inphinit\Viewing\View;

use Inphinit\Cache;
use Inphinit\Debug;
use Inphinit\File;
use Inphinit\Maintenance;
use Inphinit\Dom\Document;
use Inphinit\Http\Negotiation;
use Inphinit\Http\Response;

// Inject CSS for debug if necessary
Debug::view('before', 'debug.style');

// Display errors
Debug::view('error', 'debug.error');

// Display declared classes, functions and constants (uncomment next line for check used classes)
# Debug::view('defined', 'debug.defined');

//Display memory usage (uncomment next line for check memory peak usage and time)
# Debug::view('performance', 'debug.performance');

Route::set('GET', '/info', 'phpinfo');

Route::set('GET', '/memory', function () {
    return 'memory peak usage: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . 'MB';
});

Route::set('GET', '/examples/', function () {
    View::render('examples');
});

Route::set('GET', '/warning', function () {
    echo "Foo\n";
    echo $nonExistentVariable;
    echo "Bar\n";
    echo $_SERVER['NON_EXISTENT_INDEX'];
    echo "Baz\n";
});

Route::set('GET', '/error', function () {
    echo "Foo\n";
    undefined_function();
    echo "Bar\n";
});

Route::set('GET', '/exception', function () {
    echo "Foo\n";
    throw new \Exception('Exception sample');
    echo "Bar\n";
});

Route::set('GET', '/eval-error', function () {
    echo "Foo\n";

    eval('echo $undefined_variable;');

    echo "Bar\n";

    eval('invalid sintax');

    echo "Baz\n";
});

// Maintenance
Group::create()->domain('localhost')->path('/maintenance/')->then(function () {
    // Se a requisição vier de 127.0.0.1 irá permitir navegar, mesmo em modo de manutenção
    Maintenance::ignoreif(function () {
        return $_SERVER['REMOTE_ADDR'] === '127.0.0.1';
    });

    Route::set('GET', '/up', function () {
        Maintenance::up();
        return 'Desativou o modo manutenção para as próximas requisições';
    });

    Route::set('GET', '/down', function () {
        Maintenance::down();
        return 'Ativou o modo manutenção para as próximas requisições';
    });
});

// Group routes only HTTPS
Group::create()->secure(true)->path('/secure/')->then(function () {
    Route::set('GET', '/', function () {
        return '"Hello World" on HTTPS';
    });
});

// Group routes only HTTP
Group::create()->secure(false)->path('/nonsecure/')->then(function () {
    Route::set('GET', '/', function () {
        return '"Hello World" on HTTP';
    });
});

Group::create()->path('/treaty/')->then(function () {
    \Controller\TreatySample::action();

    /*
    Is equivant to:

    Route::set('GET', '/', 'TreatySample:getIndex');
    Route::set('ANY', '/foo-bar-baz', 'TreatySample:anyFooBarBaz');
    */
});

Group::create()->path('/resource/')->then(function () {
    \Controller\ResourceSample::action();

    /*
    Is equivant to:

    Route::set('GET', '/', 'ResourceSample:index');
    Route::set('GET', '/create', 'ResourceSample:create');
    Route::set('POST', '/', 'ResourceSample:store');
    Route::set('GET', '/{:[^/]+:}/edit', 'ResourceSample:edit');
    Route::set('GET', '/{:[^/]+:}', 'ResourceSample:show');
    Route::set('PUT', '/{:[^/]+:}', 'ResourceSample:update');
    Route::set('DELETE', '/{:[^/]+:}', 'ResourceSample:destroy');
    */
});

// Samples
Group::create()->domain('localhost')->path('/samples/')->then(function () {
    // DOM CSS-selector
    Route::set('GET', '/dom/css-selector', function () {
        $dom = new Document;
        $dom->loadHTML('<html><head></head><body><div x=\'abc"def\'>Hello World!</div></body></html>');
        $elements = $dom->query('body > div');
        echo '<pre>';
        var_dump($elements);
        echo '</pre>';
    });

    // XML to Array
    Route::set('ANY', '/dom/to-array', function () {
        echo '<pre>';

        echo "Basic sample:\n";
        $doc = new Document;
        $doc->loadXML('<root><node>contents</node></root>');
        print_r($doc->toArray());

        echo "\nCOMPLETE:\n";
        $doc = new Document;
        $doc->loadXML('<root xmlns:book="https://book.io"><node foo="bar" baz="foobar">contents</node><book:tag>baz</book:tag></root>');
        print_r($doc->toArray(Document::COMPLETE));

        echo "\nSimple:\n";
        $doc = new Document;
        $doc->loadXML('<root xmlns:book="https://book.io"><node foo="bar" baz="foobar">contents</node><book:tag>baz</book:tag></root>');
        print_r($doc->toArray(Document::SIMPLE));

        echo "\nMINIMAL:\n";
        $doc = new Document;
        $doc->loadXML('<root xmlns:book="https://book.io"><node foo="bar" baz="foobar">contents</node><book:tag>baz</book:tag></root>');
        print_r($doc->toArray(Document::MINIMAL));

        echo '</pre>';
    });

    // Array to XML
    Route::set('ANY', '/dom/from-array', function () {
    });

    // Cache with 304 status code
    Route::set('ANY', '/cache', function () {
        $cache = new Cache;

        if ($cache->cached()) return;

        return str_repeat('Hello, world! ', 1000);
    });

    // Accept headers
    Route::set('GET', '/negotiation/{:charset|custom|encoding|language|qfactor|type:}/{:all|high|low:}', function ($option, $sort) {
        $negotiation = new Negotiation;

        switch ($sort) {
            case 'high':
                $sortQFactor = Negotiation::HIGH;
                break;
            case 'low':
                $sortQFactor = Negotiation::LOW;
                break;
            default:
                $sortQFactor = Negotiation::ALL;
        }

        switch ($option) {
            case 'charset':
                $langs = $negotiation->acceptCharset($sortQFactor);
                $priority = $negotiation->getCharset($sortQFactor);
                break;
            case 'custom':
                $langs = $negotiation->header('accept-foo', $sortQFactor);
                $priority = null;
                break;
            case 'encoding':
                $langs = $negotiation->acceptEncoding($sortQFactor);
                $priority = $negotiation->getEncoding($sortQFactor);
                break;
            case 'language':
                $langs = $negotiation->acceptLanguage($sortQFactor);
                $priority = $negotiation->getLanguage($sortQFactor);
                break;
            case 'charset':
                $langs = $negotiation->acceptCharset($sortQFactor);
                $priority = $negotiation->getCharset($sortQFactor);
                break;
            default:
                $langs = $negotiation->accept($sortQFactor);
                $priority = $negotiation->getAccept($sortQFactor);
        }

        echo '<h2>Supporteds</h1>';
        echo '<pre>';
        print_r($langs);
        echo '</pre>';

        echo '<h2>Priority</h2>';
        echo '<pre>';
        var_dump($priority);
        echo '</pre>';
    });

    // Add event
    App::on('foobar', function ($arg1, $arg2) {
        print_r([$arg1, $arg2]);
    });

    // trigger event
    Route::set('ANY', '/event', function () {
        App::trigger('foobar', ['param1', microtime(true)]);
    });

    // HTTP Response headers
    Route::set('ANY', '/http/headers', function () {
        View::render('home', [ 'intro' => time() ]);

        Response::cache(30); // 30 sec

        Response::status(201);
    });

    // HTTP Response download page
    Route::set('ANY', '/http/download', function () {
        View::render('home', [ 'intro' => time() ]);

        Response::download('page.html');
    });

    // HTTP Response headers
    Route::set('ANY', '/file', function () {
        echo '<pre>';

        var_dump( File::exists(INPHINIT_SYSTEM . '/main.php') ); //Retorna true
        var_dump( File::exists(INPHINIT_SYSTEM . '/MAIN.php') ); //Retorna false
        var_dump( File::exists(INPHINIT_SYSTEM . '/Main.php') ); //Retorna false
        var_dump( File::exists(INPHINIT_SYSTEM . '/main.PHP') ); //Retorna false
        var_dump( File::exists(INPHINIT_SYSTEM . '/MAIN.PHP') ); //Retorna false

         // Retorna uma string no formato octal, exemplo: 0666
        var_dump( File::permissions(INPHINIT_SYSTEM . '/main.php') );

        // Retorna formato simbolico, exemplo: -rw-rw-rw-
        var_dump( File::permissions(INPHINIT_SYSTEM . '/main.php', true) );

        // Retorna formato simbolico, exemplo: text/x-php
        var_dump( File::mime(INPHINIT_SYSTEM . '/main.php') );

        // Retorna formato simbolico, exemplo: us-ascii
        var_dump( File::encoding(INPHINIT_SYSTEM . '/main.php') );

        echo '</pre>';
    });
});
