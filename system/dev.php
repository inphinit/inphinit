<?php

use Inphinit\App;
use Inphinit\Viewing\View;

use Inphinit\Config;
use Inphinit\Event;
use Inphinit\Maintenance;
use Inphinit\Session;

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

use Inphinit\Experimental\Http\Method;

use Controllers\TreatyController;
use Controllers\ResourceController;

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
 * - Below are samples of using the common features of the framework, you can remove everything below
 * - The codes in this document will only work in developer mode
 */

$app->action('GET', '/samples/info', function () {
    phpinfo();
});

$app->action('GET', '/samples/memory', function () {
    return 'memory peak usage: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . 'MB';
});

$app->action('GET', '/samples/', function () {
    View::render('samples');
});

// Debug samples
$app->scope('*://**/samples/debug/', function ($app, $params) {
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
});

// In development mode it will predict unloaded controllers or callables exist
$app->scope('*://**/samples/debug/invalid/function/', function ($app, $params) {
    $app->action('ANY', '/', 'undefined_function');
});

$app->scope('*://**/samples/debug/invalid/class-method/', function ($app, $params) {
    class Sample {}

    $instance = new Sample();

    $app->action('ANY', '/', [$instance, 'method']);
});

$app->scope('*://**/samples/debug/invalid/static-method/', function ($app, $params) {
    $app->action('ANY', '/', ['NotExistClass', 'method']);
});

// Maintenance toggle
$app->scope('*://localhost:**/samples/maintenance/', function ($app, $params) {
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

$app->scope('*://**/samples/treaty/', function ($app, $params) {
    TreatyController::action($app);

    /*
    Is equivant to:

    $app->action('GET', '/', 'TreatyController:getIndex');
    $app->action('ANY', '/foo-bar-baz', 'TreatyController:anyFooBarBaz');
    */
});

$app->scope('*://**/samples/resource/', function ($app, $params) {
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
$app->scope('https://**/samples/routes/samples/secure/', function ($app, $params) {
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
$app->scope('*://localhost:**/samples/routes/', function ($app, $params) {

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

    // Example: http://localhost:8000/test/foo-1000
    $app->action('GET', '/test/<id:num>', 'testCallback');

    // Example: http://localhost:8000/test/foo/abc
    $app->action('GET', '/test/foo/<name:alpha>', 'testCallback');

    // Example: http://localhost:8000/test/bar/f0f0f0
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

// DOM
$app->scope('*://localhost:**/samples/dom/', function ($app, $params) {
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
$app->scope('*://localhost:**/samples/', function ($app, $params) {
    // Add event
    Event::on('foobar', function ($arg1, $arg2) {
        print_r([$arg1, $arg2]);
    });

    // trigger event
    $app->action('ANY', '/event', function () {
        Event::trigger('foobar', ['param1', microtime(true)]);
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

        $file = __DIR__ . '/main.php';
        $folder = __DIR__;

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

    $app->action('ANY', '/session', function ($app) {
        $session = new Session('sample');

        echo 'Session ID: ', $session->getId(), '<br>';

        var_dump($session->float);
        var_dump($session->int);
        var_dump($session->octal);

        $session->float = 99.9;
        $session->int = 100;
        $session->octal = 0666;

        $session->commit(); // Save
    });

    $app->action('ANY', '/session/reset', function ($app) {
        $session = new Session('sample');

        echo 'Session ID: ', $session->getId(), '<br>';

        $session->float = null;
        $session->int = null;
        $session->octal = null;

        $session->commit(); // Save

        echo 'Reset session';
    });

    $app->action('ANY', '/session/regenerate', function ($app) {
        $session = new Session('sample');

        echo 'Previous Session ID: ', $session->getId(), '<br>';

        $session->regenerate();

        echo 'Current Session ID: ', $session->getId(), '<br>';

        // saves data that may not have been added yet
        $session->commit();
    });
});

// Utilities
$app->scope('*://localhost:**/samples/utilities/', function ($app, $params) {
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
            'a e á é í ó ú â ê ô ã õ ÿ ? #',
            '冒険エレキテ島',
            '재벌집 막내아들',
            '中山狼傳',
            'Grüß Gott',
            'Αλφαβητικός Κατάλογος',
            'жар-пти́ца',
            'هزار و یک شب'
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

        print_r($version);

        $version->major = '2';
        $version->minor = '4';
        $version->patch = '6';
        $version->prerelease = ['a', 'b', 'c'];
        $version->build = ['1', '2', '3'];

        // __toString
        echo "After: {$version}\n\n";

        $version = new Version('1.0.0+test');

        print_r($version);

        // __toString
        echo "{$version}<br>";

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
        echo "{$version}";

        echo '</pre>';
    });

    $app->action('GET', '/url', function () {
        $str = "https://usêr:pãss@sample.io/foo/../--x--/--/./ã é ô ü/user@local/Ã É Ô Ü/Αλφαβητικός/섭지코지/\r\ntest\t /?Z=1&B=2&C=3&Y=4#fragment";

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

        $url = new Url('mailto:섭지코지@Αλφαβητικός.io?subject=This%20is%20the%20subject&cc=someone_else@example.com&body=This%20is%20the%20body');
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
});

$app->scope('*://**/samples/http/', function ($app, $params) {
    Method::override();

    $app->action(['DELETE', 'PATCH', 'PUT'], '/methods', function () {
        $original = Method::original();
        $current = $_SERVER['REQUEST_METHOD'];

        echo 'Original method: ', $original, '<br>';
        echo 'Current method: ', $current, '<br>';
    });

    $app->action('ANY', '/cache', function () {
        View::render('home', [
            'items' => [],
            'version' => null,
            'time' => date('h:i:s')
        ]);

        Response::cache(30);
    });

    // HTTP Response download page
    $app->action('ANY', '/download', function () {
        View::render('home', [
            'items' => [],
            'version' => null,
            'time' => date('h:i:s')
        ]);

        Response::download('page.html');
    });

    // Accept headers
    $app->action('GET', '/negotiation', function ($app, $params) {
        $negotiation = new Negotiation();


        // accept: header
        echo '<h2>accept: (content-type)</h2>';

        $priority = $negotiation->getAccept();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->accept(Negotiation::HIGH);

        echo '<p>Types sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->accept(Negotiation::LOW);

        echo '<p>Types sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->accept(Negotiation::ALL);

        echo '<p>All types (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';


        // accept-charset: header
        echo '<hr><h2>accept-charset:</h2>';

        $priority = $negotiation->getCharset();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->acceptCharset(Negotiation::HIGH);

        echo '<p>Charsets sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->acceptCharset(Negotiation::LOW);

        echo '<p>Charsets sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->acceptCharset(Negotiation::ALL);

        echo '<p>All charsets (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';


        // accept-encoding: header
        echo '<hr><h2>accept-encoding:</h2>';

        $priority = $negotiation->getEncoding();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->acceptEncoding(Negotiation::HIGH);

        echo '<p>Encodings sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->acceptEncoding(Negotiation::LOW);

        echo '<p>Encodings sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->acceptEncoding(Negotiation::ALL);

        echo '<p>All encodings (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';


        // accept-language: header
        echo '<hr><h2>accept-language:</h2>';

        $priority = $negotiation->getLanguage();

        echo '<p>Priority: ';
        var_dump($priority);
        echo '</p>';

        $list = $negotiation->acceptLanguage(Negotiation::HIGH);

        echo '<p>Languages sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->acceptLanguage(Negotiation::LOW);

        echo '<p>Languages sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->acceptLanguage(Negotiation::ALL);

        echo '<p>All languages (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';


        // Custom header
        echo '<hr><h2>Custom header:</h2>';

        $list = $negotiation->header('accept-foo', Negotiation::HIGH);

        echo '<p>Accept-Foo: sorted with Negotiation::HIGH</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->header('accept-foo', Negotiation::LOW);

        echo '<p>Accept-foo: sorted with Negotiation::LOW</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';

        $list = $negotiation->header('accept-foo', Negotiation::ALL);

        echo '<p>All accept-foo: (Negotiation::ALL)</p>';
        echo '<pre>';
        var_dump($list);
        echo '</pre>';
    });

    $app->action('GET', '/negotiation/string', function ($app, $params) {
        $str = <<<EOT
Foo-header: FOO; q=0.1, BAR; q=0.9, BAZ, BOO; q = 0.3
Accept: application/xml; q=0.5, application/json; q=0.9
EOT;

        $negotiation = Negotiation::fromString($str);

        // accept: header
        echo '<h2>Negotiation::fromString()</h2>';

        echo '<p>String:</p><pre>', $str,'</pre><hr>';

        $priority = $negotiation->getAccept();

        echo '<p>Priority accept: header: ';
        var_dump($priority);
        echo '</p><hr>';

        $fooHeaders = $negotiation->accept(Negotiation::HIGH);

        echo '<p>Accept:</p>';
        echo '<pre>';
        var_dump($fooHeaders);
        echo '</pre>';

        $fooHeaders = $negotiation->header('FOO-HEADER', Negotiation::HIGH);

        echo '<p>Foo-Header:</p>';
        echo '<pre>';
        var_dump($fooHeaders);
        echo '</pre>';
    });

    $app->action('GET', '/negotiation/qfactor', function ($app, $params) {
        $entry = 'mesh/capsule;q=0.2,mesh/cube;q=0.9,mesh/cylinder;q=0.5,mesh/plane;q=0.4,mesh/quad;q=0.3,mesh/sphere;q=0.8';

        $customEntry = Negotiation::qFactor($entry, Negotiation::HIGH);

        echo '<p>Parse: <code>', $entry, '</code></p>';
        echo '<pre>';
        var_dump($customEntry);
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
