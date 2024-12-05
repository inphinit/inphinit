<?php

use Inphinit\App;
use Inphinit\Viewing\View;

use Inphinit\Config;
use Inphinit\Event;
use Inphinit\Maintenance;

use Inphinit\Dom\Document;

use Inphinit\Filesystem\File;
use Inphinit\Filesystem\Size;

use Inphinit\Http\Negotiation;
use Inphinit\Http\Request;
use Inphinit\Http\Response;

use Inphinit\Utility\Arrays;
use Inphinit\Utility\Strings;
use Inphinit\Utility\Version;

use Inphinit\Experimental\Http\Method;

// Inject CSS for debug if necessary
$debug->setBeforeView('debug.style');

// Display errors
$debug->setErrorView('debug.error');

// Display declared classes, functions and constants (uncomment next line for check used classes)
# $debug->setDefinedView('debug.defined');

// Display memory usage (uncomment next line for check memory peak usage and time)
# $debug->setPerformanceView('debug.performance');


/**
 * PLEASE NOTE:
 *
 * - Below are examples of using the common features of the framework, you can remove everything below
 * - The codes in this document will only work in developer mode
 */

$app->action('GET', '/info', function () {
    phpinfo();
});

$app->action('GET', '/examples/', function () {
    View::render('examples');
});

$app->action('GET', '/memory', function () {
    return 'memory peak usage: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . 'MB';
});

$app->action('GET', '/warning', function () {
    echo "Foo\n";
    echo $nonExistentVariable;
    echo "Bar\n";
    echo $_SERVER['NON_EXISTENT_INDEX'];
    echo "Baz\n";
});

$app->action('GET', '/error', function () {
    echo "Foo\n";
    undefined_function();
    echo "Bar\n";
});

$app->action('GET', '/exception', function () {
    echo "Foo\n";
    throw new \Exception('Exception sample');
    echo "Bar\n";
});

$app->action('GET', '/eval-error', function () {
    echo "Foo\n";

    eval('echo $undefined_variable;');

    echo "Bar\n";

    eval('invalid sintax');

    echo "Baz\n";
});

// In development mode it will predict unloaded controllers or callables exist
$app->scope('*://*/invalid/function/', function ($app, $params) {
    $app->action('ANY', '/', 'undefined_function');
});

$app->scope('*://*/invalid/class-method/', function ($app, $params) {
    class Sample {}

    $instance = new Sample();

    $app->action('ANY', '/', [$instance, 'method']);
});

$app->scope('*://*/invalid/static-method/', function ($app, $params) {
    $app->action('ANY', '/', ['NotExistClass', 'method']);
});

// Maintenance toggle
$app->scope('*://localhost:*/maintenance/', function ($app, $params) {
    // If the request comes from "127.0.0.1" or is in development mode, it will bypass maintenance mode
    Maintenance::bypass(function () {
        return $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || App::config('development');
    });

    $app->action('GET', '/down', function () {
        Maintenance::down();

        return 'Activated maintenance mode for the next requests';
    });

    $app->action('GET', '/up', function () {
        Maintenance::up();

        return 'Disabled maintenance mode for the next requests';
    });
});

// Group routes only HTTPS
$app->scope('https://*/secure/', function ($app, $params) {
    $app->action('GET', '/', function () {
        return '"Hello World" running on HTTPS';
    });
});

// Group routes only HTTP
$app->scope('http://*/nonsecure/', function ($app, $params) {
    $app->action('GET', '/', function () {
        return '"Hello World" running on HTTP';
    });
});

$app->scope('*://*/treaty/', function ($app, $params) {
    \Controller\TreatySample::action($app);

    /*
    Is equivant to:

    $app->action('GET', '/', 'TreatySample:getIndex');
    $app->action('ANY', '/foo-bar-baz', 'TreatySample:anyFooBarBaz');
    */
});

$app->scope('*://*/resource/', function ($app, $params) {
    \Controller\ResourceSample::action($app);

    /*
    Is equivant to:

    $app->action('GET', '/', 'ResourceSample:index');
    $app->action('GET', '/create', 'ResourceSample:create');
    $app->action('POST', '/', 'ResourceSample:store');
    $app->action('GET', '/{:[^/]+:}/edit', 'ResourceSample:edit');
    $app->action('GET', '/{:[^/]+:}', 'ResourceSample:show');
    $app->action('PUT', '/{:[^/]+:}', 'ResourceSample:update');
    $app->action('DELETE', '/{:[^/]+:}', 'ResourceSample:destroy');
    */
});

// Route patterns
$app->scope('*://localhost:*/routes/', function ($app, $params) {

    $app->action('GET', '/foo/<foo>/<bar>', function ($app, $params) {
        echo 'response from /&lt;foo>/&lt;bar>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    });

    $app->action('GET', '/foo/<foo>-<bar>', function ($app, $params) {
        echo 'response from /&lt;foo>-&lt;bar>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    });

    // Example: http://localhost:8000/article/foo-1000
    $app->action('GET', '/article/<name>/<id>', function ($app, $params) {
        if (ctype_digit($params['id'])) {
            echo 'Article ID: ', $params['id'], '<br>';
            echo 'Article name: ', $params['name'];
        } else {
            $app->status(400);
            echo 'Invalid URL';
        }
    });

    // Example: http://localhost:8000/blog/foo-1000
    $app->action('GET', '/blog/<name>-<id:num>', function ($app, $params) {
        echo 'Article ID: ', $params['id'], '<br>';
        echo 'Article name: ', $params['name'];
    });

    // Example: http://localhost:8000/test/foo-1000
    $app->action('GET', '/test/<id:num>', 'testCallback');

    // Example: http://localhost:8000/test/foo/abc
    $app->action('GET', '/test/foo/<name:alpha>', 'testCallback');

    // Example: http://localhost:8000/test/bar/f0f0f0
    $app->action('GET', '/test/bar/<barcode:alnum>', 'testCallback');

    $app->action('GET', '/decimal/<value:decimal>', 'testCallback');

    $app->action('GET', '/uuid/<value:uuid>', 'testCallback');

    $app->action('GET', '/version/<value:version>', 'testCallback');

    function testCallback($params)
    {
        echo '<h1>Results testCallback():</h1>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    }

    $app->action('GET', '/nospace/<value:nospace>', function ($app, $params) {
        echo '<h1>nospace</h1>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    });

    // custom pattern
    $app->action('GET', '/custom/<myexample:example>', function ($app, $params) {
        echo '<h1>custom pattern</h1>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    });

    $app->setPattern('example', '[A-Z]\d+');
});

// DOM
$app->scope('*://localhost:*/dom/', function ($app, $params) {
    // DOM CSS-selector
    $app->action('GET', '/css-selector', function () {
        $handle = new Document(Document::HTML);

        $handle->load('<html><head></head><body><div x=\'abc"def\'>Hello World!</div><div id=\'foo\'>bar</div></body></html>');

        echo '<pre>';

        $elements = $handle->selector()->all('body > div');
        var_dump($elements);

        $element = $handle->selector()->first('#foo');
        var_dump($element);

        var_dump(htmlentities($handle->dump($handle->root())));
        echo '</pre>';
    });

    // XML to Array
    $app->action('ANY', '/to-array', function () {
        echo '<pre>';

        $handle = new Document(Document::XML);

        $handle->load('<root xmlns:book="https://book.io"><node foo="bar" baz="foobar">contents</node><book:tag>baz</book:tag></root>');

        print_r($handle->document());

        echo "\nCOMPLETE:\n";
        print_r($handle->toArray(Document::ARRAY_COMPLETE));

        echo "\nSimple:\n";
        print_r($handle->toArray(Document::ARRAY_SIMPLE));

        echo "\nMINIMAL:\n";
        print_r($handle->toArray(Document::ARRAY_MINIMAL));

        echo '</pre>';
    });

    // Array to XML
    $app->action('ANY', '/array-to-<type>', function ($app, $params) {
        if ($params['type'] === 'html') {
            $handle = new Document(Document::HTML);

            $handle->fromArray([
                'html' => [
                    'head' => [
                        '@contents' => 'contents <title>test</title>',
                    ],
                    'body' => [
                        'main' => [
                            'p' => [
                                '@contents' => 'contents <s>test</s>',
                                'span' => [
                                    'foo',
                                    'bar',
                                    'baz'
                                ]
                            ],
                            'div' => [
                                'foo',
                                'bar',
                                'baz'
                            ],
                            '@comment' => 'test'
                        ],
                        '@attributes' => [
                            'data-foo' => 'bar',
                            'data-baz' => 'foobar'
                        ]
                    ],
                    '@attributes' => [
                        'class' => 'sample',
                        'id' => 'test'
                    ]
                ]
            ]);
        } elseif ($params['type'] === 'xml') {
            $handle = new Document(Document::XML);

            $handle->fromArray([
                'root' => [
                    'node' => [
                        '@attributes' => [
                            'foo' => 'bar',
                            'baz' => 'foobar'
                        ],
                        'foo' => [
                            'bar',
                            'baz'
                        ],
                        '@contents' => 'contents <s>test</s>',
                    ],
                    'book:tag' => 'baz',
                    '@attributes' => [
                        'class' => 'sample',
                        'xmlns:book' => 'https://book.io'
                    ],
                    '@comment' => 'foobar'
                ]
            ]);
        }

        echo '<pre>';
        print_r($handle->document());
        print_r($handle->selector()->first('.sample'));
        print_r($handle->selector()->first('node[foo=bar]'));
        var_dump(htmlentities($handle->dump($handle->root())));
        echo '</pre>';
    });

    // XML error
    $app->action('ANY', '/file-error', function () {
        Document::setSeverityLevels(Document::ERROR | Document::FATAL | Document::WARNING);

        $handle = new Document(Document::XML);
        $handle->load('public/error.xml', true);

        echo '<pre>';
        var_dump(htmlentities($handle->dump()));
        echo '</pre>';
    });
});

// Samples
$app->scope('*://localhost:*/samples/', function ($app, $params) {
    // Add event
    Event::on('foobar', function ($arg1, $arg2) {
        print_r([$arg1, $arg2]);
    });

    // trigger event
    $app->action('ANY', '/event', function () {
        Event::trigger('foobar', ['param1', microtime(true)]);
    });

    // trigger event
    $app->action('ANY', '/config', function () use ($app) {
        $config = new Config('sample');

        echo '<pre>';

        var_dump('Before:', $config);

        $config->float = 99.9;
        $config->int = 100;
        $config->octal = 0666;

        unset($config->string); // Remove

        var_dump('After:', $config);

        echo '</pre>';

        $config->commit(); // Save
    });

    // HTTP Response headers
    $app->action('ANY', '/file', function () {
        echo '<pre>';

        var_dump(File::exists(INPHINIT_SYSTEM . '/main.php')); // Returns true
        var_dump(File::exists(INPHINIT_SYSTEM . '/MAIN.php')); // Returns false
        var_dump(File::exists(INPHINIT_SYSTEM . '/Main.php')); // Returns false
        var_dump(File::exists(INPHINIT_SYSTEM . '/main.PHP')); // Returns false
        var_dump(File::exists(INPHINIT_SYSTEM . '/MAIN.PHP')); // Returns false

        // Returns a string in octal format, example: 0666
        var_dump(File::permissions(INPHINIT_SYSTEM . '/main.php'));

        // Returns symbolic format, example: -rw-rw-rw-
        var_dump(File::permissions(INPHINIT_SYSTEM . '/main.php', true));

        // Returns mimetype: text/x-php
        var_dump(File::mime(INPHINIT_SYSTEM . '/main.php'));

        // Returns enconding: us-ascii
        var_dump(File::encoding(INPHINIT_SYSTEM . '/main.php'));

        echo '</pre>';
    });

    $app->action('GET', '/filesize', function () {
        $mode = Request::get('mode');

        switch ($mode) {
            case 'com':
                $handle = new Size(Size::COM);
                break;

            case 'curl':
                $handle = new Size(Size::CURL);
                break;

            case 'system':
                $handle = new Size(Size::SYSTEM);
                break;

            default:
                Response::status(400);
                die('Invalid mode');
        }

        var_dump($handle->get('/foo/bar/bigfile.zip'));
        var_dump($handle->get('/foo/bar/bigfile.zip'));
        var_dump($handle->get('/foo/bar/bigfile.zip'));

    });
});

// Utilities
$app->scope('*://localhost:*/utilities/', function ($app, $params) {
    $app->action('GET', '/arrays', function () {

        $list = [0 => 'foo', 1 => 'bar'];
        $assoc = [0 => 'a', 1 => 'bar', 'foo' => 'bar'];

        $std = new stdClass();

        $multidimentional = [
            'Foo' => 1,
            'bar' => 2,
            'Baz' => 3,
            'moo' => [
                10 => 100,
                20 => 200,
                30 => 300,
                5 => 50,
                1 => [
                    'saitama' => 'one punch',
                    'netero' => 'human evolution',
                    'allmight' => 'symbol of Peace',
                    'meruem' => 'this is why I was born'
                ]
            ]
        ];

        echo '<pre>';

        var_dump(Arrays::iterable($list)); // Returns true
        var_dump(Arrays::iterable($std)); // Returns false

        var_dump(Arrays::indexed($list)); // Returns true
        var_dump(Arrays::associative($list)); // Returns false

        var_dump(Arrays::indexed($assoc)); // Returns false
        var_dump(Arrays::associative($assoc)); // Returns true

        Arrays::ksort($multidimentional); // same SORT_REGULAR
        print_r($multidimentional);

        Arrays::ksort($multidimentional, SORT_NUMERIC);
        print_r($multidimentional);

        Arrays::ksort($multidimentional, SORT_STRING);
        print_r($multidimentional);

        Arrays::ksort($multidimentional, SORT_LOCALE_STRING);
        print_r($multidimentional);

        Arrays::ksort($multidimentional, SORT_NATURAL);
        print_r($multidimentional);

        Arrays::ksort($multidimentional, SORT_FLAG_CASE);
        print_r($multidimentional);

        echo '</pre>';
    });

    $app->action('GET', '/strings', function () {
        echo '<pre>';
        var_dump(Strings::toAscii('a e á é í ? #'));

        var_dump(Strings::toAscii('안녕! 세계!'));

        var_dump(Strings::capitalize('foo-bar-baz'));

        var_dump(Strings::capitalize('foo bar baz', ' '));

        var_dump(Strings::capitalize('foo:bar:baz', ':', '_'));
        echo '</pre>';
    });

    $app->action('GET', '/version', function () {
        echo '<pre>';

        $version = new Version('1.0.0');

        print_r($version);

        $version->major = '2';
        $version->minor = '4';
        $version->patch = '6';
        $version->prerelease = ['a', 'b', 'c'];
        $version->build = ['1', '2', '3'];

        echo "{$version}\n\n";

        $version = new Version('1.0.0+test');

        print_r($version);

        echo "{$version}\n\n";

        echo '</pre>';
    });
});

$app->scope('*://*/http/', function ($app, $params) {
    Method::override();

    $app->action(['DELETE', 'PATCH', 'PUT'], '/methods', function () {
        $original = $_SERVER['REQUEST_METHOD'];

        Method::override();

        $current = $_SERVER['REQUEST_METHOD'];

        return "Original: {$original} - Current: {$current}";
    });

    // Accept headers
    $app->action('GET', '/negotiation/<option>/<sort>', function ($app, $params) {
        $negotiation = new Negotiation();

        switch ($params['sort']) {
            case 'high':
                $sortQFactor = Negotiation::HIGH;
                break;
            case 'low':
                $sortQFactor = Negotiation::LOW;
                break;
            default:
                $sortQFactor = Negotiation::ALL;
        }

        switch ($params['option']) {
            case 'charset':
                $langs = $negotiation->acceptCharset($sortQFactor);
                $priority = $negotiation->getCharset();
                break;
            case 'custom':
                $langs = $negotiation->header('accept-foo', $sortQFactor);
                $priority = null;
                break;
            case 'encoding':
                $langs = $negotiation->acceptEncoding($sortQFactor);
                $priority = $negotiation->getEncoding();
                break;
            case 'language':
                $langs = $negotiation->acceptLanguage($sortQFactor);
                $priority = $negotiation->getLanguage();
                break;
            case 'charset':
                $langs = $negotiation->acceptCharset($sortQFactor);
                $priority = $negotiation->getCharset();
                break;
            default:
                $langs = $negotiation->accept($sortQFactor);
                $priority = $negotiation->getAccept();
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

    // HTTP Response headers
    $app->action('ANY', '/headers', function () {
        View::render('home', ['intro' => time()]);

        Response::cache(30); // 30 sec
        Response::status(201);
    });

    // HTTP Response download page
    $app->action('ANY', '/download', function () {
        View::render('home', [
            'intro' => time()
        ]);

        Response::download('page.html');
    });
});
