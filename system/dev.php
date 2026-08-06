<?php

use Inphinit\App;
use Inphinit\Viewing\View;

use Inphinit\Config;
use Inphinit\Event;
use Inphinit\Session;
use Inphinit\Packages\Package;

use Inphinit\Dom\Document;

use Inphinit\Filesystem\File;
use Inphinit\Filesystem\Size;

use Inphinit\Http\Negotiation;
use Inphinit\Http\Request;
use Inphinit\Http\Response;

use Inphinit\Utility\Arrays;
use Inphinit\Utility\Strings;
use Inphinit\Utility\Version;
use Inphinit\Utility\Url;
use Inphinit\Utility\PropertyAccessor;

use Inphinit\Experimental\Http\CookieJar;
use Inphinit\Experimental\Http\Method;

use Controllers\Samples\TreatyController;
use Controllers\Samples\ResourceController;

use Inphinit\Experimental\Delimited\Converter;
use Inphinit\Experimental\Delimited\Csv;
use Inphinit\Experimental\Delimited\Tsv;

use Inphinit\Experimental\Cli\Console;

use Inphinit\Experimental\Http\FileResponse;

/**
 * @var Inphinit\Diagnostics\App $app
 * @var Inphinit\Diagnostics\Debug $debug
 */

// Inject CSS for debug if necessary
$debug->setBeforeView('debug.style');

// Display errors
$debug->setErrorView('debug.error');

// Display declared classes, functions and constants (uncomment next line for check used classes)
# $debug->setDefinedView('debug.defined');

// Display memory usage (uncomment next line for check memory peak usage and time)
# $debug->setPerformanceView('debug.performance');

/**
 * NOTE:
 * - Below are samples of using the common features of the framework, you can remove everything below
 * - The codes in this document will only work in developer mode
 */

$app->setNamespace('Controllers\\Samples');

$app->action('GET', '/samples/info', function ($app) {
    phpinfo();
});

$app->action('GET', '/samples/memory', function () {
    return 'memory peak usage: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . 'MB';
});

$app->action('GET', '/samples/', function () {
    View::data('environment', App::config('environment'));
    View::render('samples');
});

$app->action('ANY', '/samples/views', function () {
    View::data('other', '& " \' <b>bold</b> < >');

    View::render('header');

    View::render('samples.variables', [
        'title'     => 'Safe',
        'less'      => '&lt;',
        'greater'   => '&gt;',
        'copyright' => '&copy;',
        'euro'      => '&euro;',
        'trademark' => '&trademark;',
        'yen'       => '&yen;',
        'html'      => '<a href="javascript:console.log(\'Hi!\');" onclick="console.warn(\'Bye!\');">Test</a>',
    ]);

    View::render('samples.variables', [
        'title'     => 'Unsafe',
        'less'      => '&lt;',
        'greater'   => '&gt;',
        'copyright' => '&copy;',
        'euro'      => '&euro;',
        'trademark' => '&trademark;',
        'yen'       => '&yen;',
        'html'      => '<a href="javascript:console.log(\'Hi!\');" onclick="console.warn(\'Bye!\');">Test</a>',
    ], View::UNSAFE);
});

// Debug samples
$app->scope('/samples/debug/', function ($app, $params) {
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

        eval('!invalid syntax');

        echo "Baz\n";
    });

    $app->action('GET', '/trigger-error', function () {
        echo "Foo\n";
        trigger_error('Sample notice');
        echo "Bar\n";
    });
});

// In development mode it will predict unloaded controllers or callables exist
$app->scope('/samples/debug/invalid/function/', function ($app, $params) {
    $app->action('ANY', '/', 'undefined_function');
});

$app->scope('/samples/debug/invalid/class-method/', function ($app, $params) {
    class Sample {}

    $instance = new Sample();

    $app->action('ANY', '/', [$instance, 'method']);
});

$app->scope('/samples/debug/invalid/static-method/', function ($app, $params) {
    $app->action('ANY', '/', ['NotExistClass', 'method']);
});

// If the request comes from "127.0.0.1" or is in development mode, it will bypass maintenance mode
Event::on('maintenance', function () {
    if (in_array($_SERVER['REMOTE_ADDR'], ['::1', '127.0.0.1'])) {
        // Stop propagation and disable maintenance at runtime
        return false;
    }
});

// Maintenance toggle
$app->scope('/samples/maintenance/', function ($app, $params) {
    $app->action('GET', '/on', function () {
        App::down();
        return 'Activated maintenance mode for the next requests';
    });

    $app->action('GET', '/off', function () {
        App::up();
        return 'Disabled maintenance mode for the next requests';
    });
});

// Excute commands in web interface
$app->scope('/samples/commands/', function ($app, $params) {
    $app->action('GET', '/run', function () {
        // Equivalent to the `run hello --name Mary` command
        $output = Console::run('hello', [
            'name' => 'Mary'
        ], $status);

        echo '<pre>';

        var_dump([
            'output' => $output,
            'status' => $status
        ]);

        echo '</pre>';
    });

    $app->action('GET', '/unknown', function () {
        // Equivalent to the `run unknown` command
        $output = Console::run('unknown', [], $status);

        echo '<pre>';

        var_dump([
            'output' => $output,
            'status' => $status
        ]);

        echo '</pre>';
    });
});

$app->action('GET', '/samples/routes', function ($app) {
    echo '<pre>';
    echo htmlspecialchars(print_r($app->routes(), true));
    echo '</pre>';
});

$app->scope('/samples/routes/treaty/', function ($app, $params) {
    TreatyController::action($app);

    /*
    Is equivant to:

    $app->action('GET', '/', 'TreatyController:getIndex');
    $app->action('ANY', '/foo-bar-baz', 'TreatyController:anyFooBarBaz');
    */
});

$app->scope('/samples/routes/resource/', function ($app, $params) {
    ResourceController::action($app);

    /*
    Is equivant to:

    $app->action('GET', '/', 'ResourceController:index');
    $app->action('GET', '/create', 'ResourceController:create');
    $app->action('POST', '/', 'ResourceController:store');
    $app->action('GET', '/<id>/edit', 'ResourceController:edit');
    $app->action('GET', '/<id>', 'ResourceController:show');
    $app->action('PUT', '/<id>', 'ResourceController:update');
    $app->action('DELETE', '/<id>', 'ResourceController:destroy');
    */
});

// Group routes only HTTPS
$app->scope('https://**/samples/routes/secure/', function ($app, $params) {
    $app->action('GET', '/', function () {
        return '"Hello World" running on HTTPS';
    });
});

// Group routes only HTTP
$app->scope('http://**/samples/routes/nonsecure/', function ($app, $params) {
    $app->action('GET', '/', function () {
        return '"Hello World" running on HTTP';
    });
});

// Route patterns
$app->scope('/samples/routes/', function ($app, $params) {
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
            Response::status(400);
            echo 'Invalid URL';
        }
    });

    // Example: http://localhost:8000/blog/foo-1000
    $app->action('GET', '/blog/<name>-<id:num>', function ($app, $params) {
        echo 'Article ID: ', $params['id'], '<br>';
        echo 'Article name: ', $params['name'];
    });

    function testCallback($app, $params)
    {
        echo '<h1>Results testCallback():</h1>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    }

    $app->action('GET', '/test/<id:num>', 'testCallback');

    $app->action('GET', '/test/foo/<name:alpha>', 'testCallback');

    $app->action('GET', '/test/bar/<barcode:alnum>', 'testCallback');

    $app->action('GET', '/decimal/<value:decimal>', 'testCallback');

    $app->action('GET', '/uuid/<value:uuid>', 'testCallback');

    $app->action('GET', '/version/<value:version>', 'testCallback');

    $app->action('GET', '/nospace/<value:nospace>', function ($app, $params) {
        echo '<h1>nospace</h1>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    });

    $app->setPattern('customcode', '[A-Z]\d+');

    // custom pattern
    $app->action('GET', '/custom/<codeparam:customcode>', function ($app, $params) {
        echo '<h1>custom pattern</h1>';
        echo '<pre>';
        print_r($params);
        echo '</pre>';
    });
});

$app->scope('/samples/routes/dynamic-scope-<name:alpha>/', function ($app, $scopeParams) {
    $app->action('GET', '/route', function ($app, $params) use ($scopeParams) {
        echo '<pre>';
        echo '(from ->scope()) $scopeParams =&gt; ';
        var_dump($scopeParams);
        echo '<br>(from ->action()) $params =&gt; ';
        var_dump($params);
        echo '</pre>';
    });
});

$app->scope('/samples/routes/error/http-method/', function ($app) {
    $app->action('PURGE', '/foo', function () {});
});

$app->scope('/samples/routes/error/duplicate-methods/', function ($app) {
    $app->action(['GET', 'GET', 'POST'], '/foo', function () {});
});

$app->scope('/samples/routes/error/invalid-controller/', function ($app) {
    $app->action('GET', '/foo', 'Foo::barz');
});

$app->scope('/samples/routes/error/__construct/', function ($app) {
    $app->action('GET', '/foo', 'TreatyController::__construct');
});

$app->scope('/samples/routes/error/set-namespace/', function ($app) {
    $app->setNamespace('Invalid\\\\Namespace');
});

$app->scope('/samples/routes/error/set-pattern/', function ($app) {
    $app->setPattern('abc', 'abc[de{fg');
});

// DOM
$app->scope('/samples/dom/', function ($app, $params) {
    // DOM CSS-selector
    $app->action('GET', '/css-selector', function () {
        $handle = new Document(Document::HTML);

        $handle->load('<html><head></head><body><div x=\'abc"def\'>Hello World!</div><div id=\'foo\'>bar</div></body></html>');

        $size = $handle->selector()->count('body div');

        echo '"body div": ';

        var_dump($size);

        echo '<pre>';

        $elements = $handle->selector()->all('body > div');
        var_dump($elements);

        $element = $handle->selector()->first('#foo');
        var_dump($element);

        var_dump(htmlspecialchars($handle->dump($handle->root())));
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
        var_dump(htmlspecialchars($handle->dump($handle->root())));
        echo '</pre>';
    });

    // XML error
    $app->action('ANY', '/file-error', function () {
        Document::setSeverityLevels(Document::ERROR|Document::FATAL|Document::WARNING);

        $handle = new Document(Document::XML);
        $handle->load('public/error.xml', true);

        echo '<pre>';
        var_dump(htmlspecialchars($handle->dump($handle->document())));
        echo '</pre>';
    });
});

// Samples
$app->scope('/samples/', function ($app, $params) {
    // No priority specified (priority = 0)
    Event::on('foobar', function ($arg1, $arg2) {
        echo "1° function: {$arg1}, {$arg2} (priority = 0)<br>";
    });

    // No priority specified (priority = 0)
    Event::on('foobar', function ($arg1, $arg2) {
        echo "2° function: {$arg1}, {$arg2} (priority = 0)<br>";
    });

    // Low priority (priority = -1)
    Event::on('foobar', function ($arg1, $arg2) {
        echo "3° function: {$arg1}, {$arg2} (priority = -20)<br>";

        // Stop propagation
        return false;
    }, -20);

    // (priority = -300)
    // Note: It will not be executed because a higher-priority event stopped the propagation
    Event::on('foobar', function ($arg1, $arg2) {
        echo "4° function: {$arg1}, {$arg2} (priority = -300)<br>";
    }, -300);

    // High priority (priority = 1)
    Event::on('foobar', function ($arg1, $arg2) {
        echo "5° function: {$arg1}, {$arg2} (priority = HIGH_PRIORITY)<br>";
    }, Event::HIGH_PRIORITY);

    // (priority = 600)
    Event::on('foobar', function ($arg1, $arg2) {
        echo "6° function: {$arg1}, {$arg2} (priority = 600)<br>";
    }, 600);

    // Low priority (priority = -1)
    Event::on('foobar', function ($arg1, $arg2) {
        echo "7° function: {$arg1}, {$arg2} (priority = LOW_PRIORITY)<br>";
    }, Event::LOW_PRIORITY);

    // trigger event
    $app->action('ANY', '/event', function () {
        Event::trigger('foobar', ['foo', 'bar']);
    });

    $app->action('ANY', '/config', function ($app) {
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

    $app->action('ANY', '/file', function () {
        echo '<pre>';

        $files = [
            'MAIN.PHP',
            'main.php',
            'MAIN.php',
            'Main.php',
            'main.PHP'
        ];

        echo "Check file exists with file_exists():\n";

        foreach ($files as $file) {
            $file = INPHINIT_SYSTEM . '/' . $file;
            echo "{$file}: ";
            var_dump(file_exists($file));
        }

        echo "Check file exists (case-sensitive any systems) with File::exists():\n";

        foreach ($files as $file) {
            $file = INPHINIT_SYSTEM . '/' . $file;
            echo "{$file}: ";
            var_dump(File::exists($file));
        }

        // Returns a string in octal format, example: 0666
        echo 'Permissions: ';
        var_dump(File::permissions(INPHINIT_SYSTEM . '/main.php'));

        // Returns symbolic format, example: -rw-rw-rw-
        echo 'Permissions: ';
        var_dump(File::permissions(INPHINIT_SYSTEM . '/main.php', true));

        echo '</pre>';
    });

    $app->action('GET', '/filesize', function () {
        $handleFallback = new Size(); // Same new Size(Size::COM|Size::CURL|Size::SYSTEM)
        $handleCom = new Size(Size::COM);
        $handleCurl = new Size(Size::CURL);
        $handleSystem = new Size(Size::SYSTEM);

        $file = 'system/main.php';

        echo "{$file} file size:<pre>";

        echo 'With fallback: Size::COM|Size::CURL|Size::SYSTEM: ';

        try {
            var_dump($handleFallback->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }

        echo '<br>With Size::COM: ';

        try {
            var_dump($handleCom->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }

        echo '<br>With Size::CURL: ';

        try {
            var_dump($handleCurl->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }

        echo '<br>With Size::SYSTEM: ';

        try {
            var_dump($handleSystem->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }
        echo '</pre><hr>';

        $file = 'invalid.txt';

        echo "{$file} file size:<pre>";

        echo 'Size::COM: ';

        try {
            var_dump($handleCom->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }

        echo '<br>Size::CURL: ';

        try {
            var_dump($handleCurl->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }

        echo '<br>Size::SYSTEM: ';

        try {
            var_dump($handleSystem->get($file));
        } catch (Exception $e) {
            echo '(' . $e->getCode() . ') ' . $e->getMessage() . "\r\n";
        }
        echo '</pre>';

    });

    $app->action('ANY', '/cookie', function ($app) {
        echo '123';

        $jar = new CookieJar('sample');

        $jar->foo = 1;
        $jar->bar = 2.5;
        $jar->baz = 'text';
        $jar->boo = null;

        $jar->setExpires('+1 week');
        $jar->setHttpOnly(true);
        $jar->setPartitioned(true);

        $jar->send();
    });

    $app->action('ANY', '/session', function ($app) {
        $session = new Session('session');

        echo 'Session ID: ', $session->getId(), '<br>';

        echo '<h2>Before:</h2>';
        echo '<pre>';
        var_dump($session->float);
        var_dump($session->int);
        var_dump($session->octal);
        echo '</pre>';

        $session->float = microtime(true);
        $session->int = time();
        $session->octal = 0666;

        echo '<h2>After:</h2>';
        echo '<pre>';
        var_dump($session->float);
        var_dump($session->int);
        var_dump($session->octal);
        echo '</pre>';

        $session->commit(); // Save
    });

    $app->action('ANY', '/session/reset', function ($app) {
        $session = new Session('session');

        echo 'Session ID: ', $session->getId(), '<br>';

        $session->float = null;
        $session->int = null;
        $session->octal = null;

        // saves data that may not have been added yet
        $session->commit();

        echo 'Reset session';
    });

    $app->action('ANY', '/session/regenerate', function ($app) {
        $session = new Session('session');

        $previous = $session->getId();

        $session->regenerate();

        $current = $session->getId();

        echo 'Previous Session ID: ', $previous, '<br>';
        echo 'Current Session ID: ', $current, '<br>';

        // saves data that may not have been added yet
        $session->commit();
    });

    $app->action('GET', '/sendfile/header', function () {
        $dir = __DIR__;

        // headers to download response
        Response::download('sample.txt');

        // Internal redirect to private file (supported by Built-in web server on Inphinit)
        header("X-Accel-Redirect: {$dir}/storage/private/sample.txt");
    });

    $app->action('GET', '/sendfile/<mode>', function ($app, $params) {
        $dir = __DIR__;
        $path = "{$dir}/storage/private/sample.txt";

        $handle = new FileResponse($path, 'output.txt');

        switch ($params['mode']) {
            case 'x-accel-redirect':
                $handle->send(FileResponse::ACCEL);
                break;

            case 'x-sendfile':
                $handle->send(FileResponse::SENDFILE);
                break;

            case 'fallback':
                $handle->send(FileResponse::FALLBACK);
                break;

            case 'alternate':
                $handle->send(FileResponse::ACCEL|FileResponse::SENDFILE);
                break;

            default:
                die('Invalid mode');
        }
    });

    // Packages
    $app->action('GET', '/packages', function () {
        // The information is not available when the installation is done via Git (or manually).
        $packages = [
            'inphinit/framework',
            'phpstan/phpstan',
        ];

        foreach ($packages as $package) {
            echo "{$package} version: ", Package::info($package, Package::VERSION);
            echo "<br>{$package} type: ", Package::info($package, Package::TYPE);
            echo "<br>{$package} source: ", Package::info($package, Package::SOURCE);
            echo "<br>{$package} time: ", Package::info($package, Package::TIME);
            echo "<br>{$package} url: ", Package::info($package, Package::URL);
            echo "<br>{$package} description: ", Package::info($package, Package::DESCRIPTION);
            echo '<hr>';
        }
    });
});

// Utilities
$app->scope('/samples/utilities/', function ($app, $params) {
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

        echo '<h2>List</h2>';

        echo '<code>Arrays::indexed($list)</code>: ';
        var_dump(Arrays::indexed($list));
        echo '<br>';

        echo '<code>Arrays::iterable($list)</code>: ';
        var_dump(Arrays::iterable($list));
        echo '<br>';

        echo '<pre>$list = ';
        var_export($list);
        echo '</pre><hr>';

        echo '<h2>Associative</h2>';

        echo '<code>Arrays::indexed($assoc)</code>: ';
        var_dump(Arrays::indexed($assoc));
        echo '<br>';

        echo '<code>Arrays::iterable($assoc)</code>: ';
        var_dump(Arrays::iterable($assoc));
        echo '<br>';

        echo '<pre>$assoc = ';
        var_export($assoc);
        echo '</pre><hr>';

        echo '<h2>stdClass</h2>';

        echo '<code>Arrays::iterable($std)</code>: ';
        var_dump(Arrays::iterable($std));
        echo '<br>';

        echo '<pre>$std = ';
        var_export($std);
        echo '</pre><hr>';

        echo '<h2>Sort multidimentional array</h2>';

        echo 'Original:<br>';

        echo '<pre>';
        print_r($multidimentional);

        echo '<br>Arrays::ksort($multidimentional, SORT_REGULAR):<br>';
        Arrays::ksort($multidimentional);
        print_r($multidimentional);

        echo '<br>Arrays::ksort($multidimentional, SORT_NUMERIC):<br>';
        Arrays::ksort($multidimentional, SORT_NUMERIC);
        print_r($multidimentional);

        echo '<br>Arrays::ksort($multidimentional, SORT_STRING):<br>';
        Arrays::ksort($multidimentional, SORT_STRING);
        print_r($multidimentional);

        echo '<br>Arrays::ksort($multidimentional, SORT_LOCALE_STRING):<br>';
        Arrays::ksort($multidimentional, SORT_LOCALE_STRING);
        print_r($multidimentional);

        echo '<br>Arrays::ksort($multidimentional, SORT_NATURAL):<br>';
        Arrays::ksort($multidimentional, SORT_NATURAL);
        print_r($multidimentional);

        echo '<br>Arrays::ksort($multidimentional, SORT_FLAG_CASE):<br>';
        Arrays::ksort($multidimentional, SORT_FLAG_CASE);
        print_r($multidimentional);
        echo '</pre>';
    });

    $app->action('GET', '/strings', function () {
        echo '<h2>String to ASCII</h2>';

        $items = [
            'a e á é í ó ú â ê ô ã õ ÿ',
            '冒険エレキテ島',
            '재벌집 막내아들',
            '中山狼傳',
            'Grüß Gott',
            'Αλφαβητικός Κατάλογος',
            'жар-пти́ца',
            'هزار و یک شب',
            'Y̶o̶u̶r̶ ̶N̶a̶m̶e̶',
            'Y͓̽o͓̽u͓̽r͓̽ ͓̽N͓̽a͓̽m͓̽e͓̽',
        ];

        foreach ($items as $str) {
            echo 'Original: ', $str, '<br>';
            echo 'ASCII: ', Strings::toAscii($str), '<hr>';
        }

        echo '<h2>Capitalize</h2>';

        echo '<pre>';
        var_dump(Strings::capitalize('foo-bar-baz'));

        var_dump(Strings::capitalize('foo bar baz', ' '));

        var_dump(Strings::capitalize('foo:bar:baz', ':', '_'));
        echo '</pre>';
    });

    $app->action('GET', '/version', function () {
        echo '<pre>';

        $version = new Version('1.0.0');

        // __toString
        echo "Before: {$version}\n\n";

        print_r($version);

        $version->major = '2';
        $version->minor = '4';
        $version->patch = '6';
        $version->prerelease = ['a', 'b', 'c'];
        $version->build = ['1', '2', '3'];

        // __toString
        echo "After: {$version}\n\n";

        $version = new Version('1.0.0-alpha-a.b-c-somethinglong+build.1-aef.1-its-okay');

        // __toString
        echo "Before: {$version}\n\n";

        print_r($version);

        try {
            $version->major = 'a';
        } catch (\Exception $e) {
            echo '$version->major: ', $e->getMessage(), '<br>';
        }

        try {
            $version->minor = false;
        } catch (\Exception $e) {
            echo '$version->minor: ', $e->getMessage(), '<br>';
        }

        try {
            $version->patch = [];
        } catch (\Exception $e) {
            echo '$version->patch: ', $e->getMessage(), '<br>';
        }

        try {
            $version->prerelease = 'test';
        } catch (\Exception $e) {
            echo '$version->prerelease: ', $e->getMessage(), '<br>';
        }

        try {
            $version->build = 'test';
        } catch (\Exception $e) {
            echo '$version->build: ', $e->getMessage(), '<br>';
        }

        // __toString
        echo "After: {$version}\n\n";

        $beta2 = new Version('1.0.0-beta2');
        $dev1 = new Version('1.0.0-dev1');
        $release = new Version('1.0.0');

        echo '$beta2 compare to $beta2: ', $beta2->compare($beta2), '<br>';

        echo '$beta2 compare to $dev1: ', $beta2->compare($dev1), '<br>';
        echo '$beta2 compare to $release: ', $beta2->compare($release), '<br>';

        echo '$dev1 compare to $beta2: ', $dev1->compare($beta2), '<br>';
        echo '$dev1 compare to $release: ', $dev1->compare($release), '<br>';

        echo '$release compare to $beta2: ', $release->compare($beta2), '<br>';
        echo '$release compare to $dev1: ', $release->compare($dev1), '<br>';

        echo '</pre>';
    });

    $app->action('GET', '/url', function () {
        $str = "http://usêr:pãss@sample.io:443/foo/../--x--/--/./ã é ô ü/user@local/Ã É Ô Ü/[½] [‱]/①Ⓐ➊❶⓫⓿⑴/Αλφαβητικός/섭지코지/\r\ntest\t /?Z=1&B=2&C=3&Y=4#fragment";

        echo 'Original: ', $str, '<hr>';

        $url = new Url($str);
        $url->normalize();
        echo '<h2>Canon URL path:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url($str);
        $url->normalize(Url::PATH_ASCII);
        echo '<h2>PATH_ASCII:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url($str);
        $url->normalize(Url::PATH_UNICODE);
        echo '<h2>PATH_UNICODE:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url($str);
        $url->normalize(Url::PATH_SLUG);
        echo '<h2>PATH_SLUG:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url($str);
        $url->normalize(Url::SORT_QUERY);
        echo '<h2>SORT_QUERY:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url($str);
        $url->normalize(Url::PATH_UNICODE|Url::PATH_SLUG|Url::SORT_QUERY);
        echo '<h2>PATH_UNICODE+PATH_SLUG+SORT_QUERY:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url($str);
        $url->normalize(Url::PATH_ASCII|Url::PATH_SLUG|Url::SORT_QUERY);
        echo '<h2>PATH_ASCII+PATH_SLUG+SORT_QUERY:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url('/foo/../bar/./á é í/user@localhost/Á É Í Ó/Αλφαβητικός/');
        $url->normalize(Url::PATH_ASCII|Url::PATH_SLUG);
        echo '<h2>Only path (PATH_ASCII+PATH_SLUG):</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url('C:\\foo\\..\bar\\.\á é í\\userlocalhost\\Á É Í Ó\\Αλφαβητικός\\');
        $url->normalize();
        echo '<h2>Windows path:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = new Url('mailto:섭지코지@Αλφαβητικός.io?subject=This is the+subject&cc=someone_else@example.com&body=This is the+body http://example.io/2000/svg');
        $url->normalize();
        echo '<h2>Using mailto:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $path = '/home/foo/../bar/./test.txt';

        echo '<h2>Canon path:</h2>';
        echo '<p>Before: ', $path,'</p>';

        $path = Url::canonpath($path);
        echo '<p>After: ', $path,'</p><hr>';

        $path = 'C:\\home\\foo\\..\\bar\\.\\test.txt';

        echo '<h2>Canon path:</h2>';
        echo '<p>Before: ', $path,'</p>';

        $path = Url::canonpath($path);
        echo '<p>After: ', $path,'</p>';
    });

    $app->action('GET', '/others', function () {
        $items = [
            'entries' => [
              'a',
              'b',
              'c'
            ],
            'foo' => [
                'bar' => [
                    'baz' => [
                        'deep' => 777
                    ]
                ]
            ],
        ];

        // Equivalent to $items['entries'][0]
        var_dump(PropertyAccessor::getValue('entries.0', $items));

        // Equivalent to $items['entries'][1]
        var_dump(PropertyAccessor::getValue('entries.1', $items));

        // Equivalent to $items['entries'][2]
        var_dump(PropertyAccessor::getValue('entries.2', $items));

        // Equivalent to $items['foo']['bar']['baz']['deep']
        var_dump(PropertyAccessor::getValue('foo.bar.baz.deep', $items));

        // Equivalent to $items['foo']['bar']['baz']['invalid']
        var_dump(PropertyAccessor::getValue('foo.bar.baz.invalid', $items));

        // Equivalent to $items['foo']['bar']['baz']['invalid']
        var_dump(PropertyAccessor::getValue('foo.bar.baz.invalid', $items, 'Alternative value!'));

        // Equivalent to $items['foo']['bar']['baz']
        var_dump(PropertyAccessor::getValue('foo.bar.baz', $items));

        // Equivalent to $items['foo']['bar']
        var_dump(PropertyAccessor::getValue('foo.bar', $items));
    });
});

$app->scope('/samples/http/', function ($app, $params) {
    Method::override();

    $app->action(['DELETE', 'PATCH', 'PUT'], '/methods', function () {
        $original = Method::original();
        $current = $_SERVER['REQUEST_METHOD'];

        echo 'Original method: ', $original, '<br>';
        echo 'Current method: ', $current, '<br>';
    });

    $app->action('ANY', '/cache', function () {
        View::render('home', [
            'environment' => App::config('environment'),
            'items' => [],
            'version' => null,
            'time' => date('h:i:s')
        ]);

        Response::cache(30);
    });

    $app->action('GET', '/is', function () {
        echo '<pre>';

        // Returns true if sent Sec-GPC: 1 header in request, otherwise returns false.
        var_dump('gpc:', Request::is('gpc'));

        // Returns true if sent X-Pjax header in request, otherwise returns false.
        var_dump('pjax:', Request::is('pjax'));

        // Returns true if sent prefetch header (e.g., Sec-Purpose, x-purpose, purpose, x-moz) in request, otherwise returns false.
        var_dump('prefetch:', Request::is('prefetch'));

        // Returns true if sent Save-Data: on header in request, otherwise returns false.
        var_dump('save:', Request::is('save'));

        // Returns true if using HTTPS, otherwise returns false.
        var_dump('secure:', Request::is('secure'));

        // Returns true if sent X-Requested-With: XMLHttpRequest header in request, otherwise returns false.
        var_dump('xhr:', Request::is('xhr'));

        echo '</pre>';
    });

    // HTTP Response download page
    $app->action('ANY', '/download', function () {
        View::render('home', [
            'environment' => App::config('environment'),
            'items' => [],
            'version' => null,
            'time' => date('h:i:s')
        ]);

        Response::download('page.html');
    });

    // Accept headers
    $app->action('GET', '/negotiation', function ($app, $params) {
        $negotiation = new Negotiation();

        echo '<h2>accept: (content-type)</h2>';

        $priority = $negotiation->topContentType();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->contentTypes(Negotiation::HIGH);

        echo '<p>Types sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->contentTypes(Negotiation::LOW);

        echo '<p>Types sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->contentTypes(Negotiation::ALL);

        echo '<p>All types (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        // accept-encoding: header
        echo '<hr><h2>accept-encoding:</h2>';

        $priority = $negotiation->topEncoding();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->encodings(Negotiation::HIGH);

        echo '<p>Encodings sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->encodings(Negotiation::LOW);

        echo '<p>Encodings sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->encodings(Negotiation::ALL);

        echo '<p>All encodings (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';


        // accept-language: header
        echo '<hr><h2>accept-language:</h2>';

        $priority = $negotiation->topLanguage();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->languages(Negotiation::HIGH);

        echo '<p>Languages sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->languages(Negotiation::LOW);

        echo '<p>Languages sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->languages(Negotiation::ALL);

        echo '<p>All languages (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';


        // Custom header
        echo '<hr><h2>Custom header:</h2>';

        $list = $negotiation->entries('accept-foo', Negotiation::HIGH);

        echo '<p>Accept-Foo: sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->entries('accept-foo', Negotiation::LOW);

        echo '<p>Accept-foo: sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->entries('accept-foo', Negotiation::ALL);

        echo '<p>All accept-foo: (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';
    });

    $app->action('GET', '/negotiation/string', function ($app, $params) {
        $str = <<<EOT
TE: gzip; q=1.0, deflate; q=0.8
Custom: foo, bar;q=0.9, baz;q=0.8, quux;q=0.7, waldo;q=0.5
EOT;

        $negotiation = Negotiation::fromString($str);

        $te = $negotiation->top('te', Negotiation::HIGH);
        var_dump($te);

        $te = $negotiation->entries('te', Negotiation::HIGH);
        print_r($te);

        $te = $negotiation->top('custom', Negotiation::HIGH);
        var_dump($te);

        $custom = $negotiation->entries('custom', Negotiation::HIGH);
        print_r($custom);
    });

    $app->action('GET', '/negotiation/qfactor', function ($app, $params) {
        $entry = 'mesh/capsule;q=0.2,mesh/cube;q=0.9,mesh/cylinder;q=0.5,mesh/plane;q=0.4,mesh/quad;q=0.3,mesh/sphere;q=0.8';

        $customEntry = Negotiation::qFactor($entry, Negotiation::HIGH);

        echo '<p>Parse: <code>', $entry, '</code></p>';
        echo '<pre>';
        print_r($customEntry);
        echo '</pre>';
    });

    $app->action('GET', '/get', function () {
        $contents = Request::get('foo');
        echo '<h2>Request::get(foo):</h2>';
        echo '<pre>';
        var_dump($contents);
        echo '</pre>';

        $contents = Request::get('foo.bar');
        echo '<h2>Request::get(foo.bar):</h2>';
        echo '<pre>';
        var_dump($contents);
        echo '</pre>';

        $contents = Request::get('foo.bar.baz');
        echo '<h2>Request::get(foo.bar.baz):</h2>';
        echo '<pre>';
        var_dump($contents);
        echo '</pre>';

        $contents1 = Request::get('foo.list.0');
        $contents2 = Request::get('foo.list.1');
        echo '<h2>Request::get(foo.list.0) and Request::get(foo.list.1):</h2>';
        echo '<pre>';
        var_dump($contents1, $contents2);
        echo '</pre>';

        $contents = Request::get('foo.bar.other');
        echo '<h2>Request::get(foo.bar.other):</h2>';
        echo '<pre>';
        var_dump($contents);
        echo '</pre>';
    });
});

// Login for dashboard + dashboard samples
$app->scope('/samples/dashboard/', function ($app, $params) {
    $auth = new Inphinit\Experimental\Authentication\Auth();
    $app->auth = $auth;

    // Login form
    $app->action('GET', '/auth/', 'Dashboard\AuthController::login');

    // Validate login and password, if valid create a session
    $app->action('POST', '/auth/login', 'Dashboard\AuthController::check');

    // Form for create a new user
    $app->action('GET', '/auth/register', 'Dashboard\AuthController::register');

    // Create a new user account
    $app->action('POST', '/auth/signup', 'Dashboard\AuthController::signup');

    // Logout
    $app->action('GET', '/auth/logout', 'Dashboard\AuthController::logout');

    $app->action('GET', '/', 'Dashboard\DashboardController::home');
    $app->action('GET', '/exit', 'Dashboard\DashboardController::confirmExit');
});

// Login for API + API samples
$app->scope('/samples/api/', function ($app, $params) {
    $auth = new Inphinit\Experimental\Authentication\Auth();
    $app->auth = $auth;

    // Validate login and password, if valid return a TOKEN
    $app->action('POST', '/auth/login', 'Api\AuthController::check');

    // Create a new user account
    $app->action('POST', '/auth/signup', 'Api\AuthController::signup');

    // Logout
    $app->action('GET', '/auth/logout', 'Api\AuthController::logout');

    // Samples
    $app->action('GET', '/products/', 'Api\ProductsController::list');
    $app->action('GET', '/products/<id>', 'Api\ProductsController::show');
});

$app->scope('/samples/csv/', function ($app) {
    $storage = INPHINIT_SYSTEM . '/storage/samples/csv';

    $app->action('GET', '/', function () use ($storage) {
        $handle = new Csv($storage . '/source.csv');

        $handle->setEndOfLine("\n");

        echo '<h2>Headers:</h2>';
        echo '<pre>';
        var_dump($handle->getHeaders());
        echo '</pre>';

        echo '<h2>Contents:</h2>';
        echo '<pre>';

        while ($line = $handle->fetch()) {
            var_dump($line);
        }

        echo '</pre>';

        echo '<h2>Contents (columns):</h2>';
        echo '<pre>';

        $handle->setFlags(Csv::MODE_COLUMN|Csv::SKIP_EMPTY|Csv::SKIP_HEADER);

        $handle->setFilter(function (array &$fields, $index) {
            foreach ($fields as &$field) {
                $field = stripcslashes($field);
            }
        });

        // Rewind and refresh headers
        $handle->refresh();

        while ($line = $handle->fetch()) {
            var_dump($line);
        }

        echo '</pre>';
        echo '<h2>Contents (DTO):</h2>';
        echo '<pre>';

        $handle->setDataTransferObject('stdClass');

        // Rewind and refresh headers
        $handle->refresh();

        while ($line = $handle->fetch()) {
            var_dump($line);
        }

        echo '</pre>';
    });

    $app->action('GET', '/convert', function () use ($storage) {
        $handle = new Csv($storage . '/source.csv');

        $converter = new Converter($handle);

        // // Output like: [["header 1","header 2","header 3"],["foo","bar","baz"]]
        // $converter->json($storage . '/output[index].json', false);

        // // Output like: [{"header 1":"foo","header 2":"bar","header 3":"baz"}]
        // $handle->converter()->json($storage . '/output[pairs].json', true, JSON_PRETTY_PRINT);

        // $handle->converter()->csv($storage . '/output.csv', ';', '"', "\r\n");

        // $handle->converter()->tsv($storage . '/output.tsv');

        $xml = new DOMDocument;
        $main = $xml->createElement('Main');
        $xml->appendChild($main);

        $handle->setFilter(function (array &$fields, $index) {
            // Handle values present in the headers.
            if ($index === 0) {
                foreach ($fields as &$field) {
                    $field = preg_replace('#[^\w]+#', '-', $field);
                    $field = preg_replace('#-{2,}#', '-', $field);
                }
            } else {
                foreach ($fields as &$field) {
                    $field = stripcslashes($field);
                }
            }
        });

        $handle->converter()->dom($main);

        Response::type('application/xml');

        echo $xml->saveXML();
    });

    $app->action('GET', '/index.json', function () use ($storage) {
        Response::type('application/json');
        header('X-Accel-Redirect: ' . $storage . '/output[index].json');
    });

    $app->action('GET', '/pairs.json', function () use ($storage) {
        Response::type('application/json');
        header('X-Accel-Redirect: ' . $storage . '/output[pairs].json');
    });

    $app->action('GET', '/output', function () use ($storage) {
        Response::type('text/csv');
        header('Content-Disposition: inline; filename="output.csv"');
        header('X-Accel-Redirect: ' . $storage . '/output.csv');
    });

    $app->action('GET', '/tsv', function () use ($storage) {
        Response::type('text/tab-separated-values');
        header('Content-Disposition: inline; filename="output.tsv"');
        header('X-Accel-Redirect: ' . $storage . '/output.tsv');
    });
});

$app->scope('/samples/tsv/', function ($app) {
    $storage = INPHINIT_SYSTEM . '/storage/samples/tsv';

    $app->action('GET', '/', function () use ($storage) {
        $handle = new Tsv($storage . '/source.tsv');

        echo '<h2>Headers:</h2>';
        echo '<pre>';
        var_dump($handle->getHeaders());
        echo '</pre>';

        echo '<h2>Contents:</h2>';
        echo '<pre>';

        while ($line = $handle->fetch()) {
            var_dump($line);
        }

        echo '</pre>';
        echo '<h2>Contents (columns):</h2>';
        echo '<pre>';

        $handle->setFlags(Csv::MODE_COLUMN|Csv::SKIP_EMPTY|Csv::SKIP_HEADER);

        // Rewind and refresh headers
        $handle->refresh();

        while ($line = $handle->fetch()) {
            var_dump($line);
        }

        echo '</pre>';
        echo '<h2>Contents (DTO):</h2>';
        echo '<pre>';

        $handle->setDataTransferObject('stdClass');

        // Rewind and refresh headers
        $handle->refresh();

        while ($line = $handle->fetch()) {
            var_dump($line);
        }

        echo '</pre>';
    });

    $app->action('GET', '/convert', function () use ($storage) {
        $handle = new Tsv($storage . '/source.tsv');

        $handle->converter()
               ->json($storage . '/output[index].json', false, JSON_PRETTY_PRINT)
               ->json($storage . '/output[pairs].json', true)
               ->csv($storage . '/output.csv', ';')
               ->tsv($storage . '/output.tsv');

        $xml = new DOMDocument;
        $main = $xml->createElement('Main');
        $xml->appendChild($main);

        $handle->setFilter(function (array &$fields, $index) {
            // Handle values present in the headers.
            if ($index === 0) {
                foreach ($fields as &$field) {
                    $field = preg_replace('#[^\w]+#', '-', $field);
                    $field = preg_replace('#-{2,}#', '-', $field);
                }
            } else {
                foreach ($fields as &$field) {
                    $field = stripcslashes($field);
                }
            }
        });

        $handle->converter()->dom($main);

        Response::type('application/xml');

        echo $xml->saveXML();
    });

    $app->action('GET', '/index.json', function () use ($storage) {
        Response::type('application/json');
        header('X-Accel-Redirect: ' . $storage . '/output[index].json');
    });

    $app->action('GET', '/pairs.json', function () use ($storage) {
        Response::type('application/json');
        header('X-Accel-Redirect: ' . $storage . '/output[pairs].json');
    });

    $app->action('GET', '/output', function () use ($storage) {
        Response::type('text/tab-separated-values');
        header('Content-Disposition: inline; filename="output.tsv"');
        header('X-Accel-Redirect: ' . $storage . '/output.tsv');
    });

    $app->action('GET', '/csv', function () use ($storage) {
        Response::type('text/csv');
        header('Content-Disposition: inline; filename="output.csv"');
        header('X-Accel-Redirect: ' . $storage . '/output.csv');
    });
});
