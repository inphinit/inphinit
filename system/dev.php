<?php

use Inphinit\App;
use Inphinit\Debug;

use Inphinit\Routing\Route;
use Inphinit\Routing\Group;
use Inphinit\Viewing\View;

use Inphinit\Config;
use Inphinit\Maintenance;
use Inphinit\Session;

use Inphinit\Dom\Document;

use Inphinit\File;

use Inphinit\Http\Negotiation;
use Inphinit\Http\Request;
use Inphinit\Http\Response;

use Inphinit\Helper;
use Inphinit\Uri;

use Controller\Samples\TreatyController;
use Controller\Samples\ResourceController;

// Inject CSS for debug if necessary
Debug::view('before', 'debug.style');

// Display errors
Debug::view('error', 'debug.error');

// Display declared classes, functions and constants (uncomment next line for check used classes)
# Debug::view('defined', 'debug.defined');

// Display memory usage (uncomment next line for check memory peak usage and time)
# Debug::view('performance', 'debug.performance');

/**
 * PLEASE NOTE:
 *
 * - Below are samples of using the common features of the framework, you can remove everything below
 * - The codes in this document will only work in developer mode
 */

Route::set('GET', '/samples/info', function () {
    phpinfo();
});

Route::set('GET', '/samples/memory', function () {
    return 'memory peak usage: ' . round(memory_get_peak_usage() / 1024 / 1024, 3) . 'MB';
});

Route::set('GET', '/samples/', function () {
    View::render('samples');
});

// Debug samples
Group::create()->path('/samples/debug/')->then(function () {
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

        eval('!invalid syntax');

        echo "Baz\n";
    });

    Route::set('GET', '/trigger-error', function () {
        echo "Foo\n";
        trigger_error('Sample notice');
        echo "Bar\n";
    });
});

// Maintenance toggle
Group::create()->path('/samples/maintenance/')->then(function () {
    // If the request comes from "127.0.0.1" or is in development mode, it will bypass maintenance mode
    Maintenance::ignoreif(function () {
        return $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || App::config('development');
    });

    Route::set('GET', '/down', function () {
        Maintenance::down();

        return 'Activated maintenance mode for the next requests';
    });

    Route::set('GET', '/up', function () {
        Maintenance::up();

        return 'Disabled maintenance mode for the next requests';
    });
});

Group::create()->prefixNS('Samples')->path('/samples/routes/treaty/')->then(function () {
    TreatyController::action();

    /*
    Is equivant to:

    Route::set('GET', '/', 'TreatyController:getIndex');
    Route::set('ANY', '/foo-bar-baz', 'TreatyController:anyFooBarBaz');
    */
});

Group::create()->prefixNS('Samples')->path('/samples/routes/resource/')->then(function () {
    ResourceController::action();

    /*
    Is equivant to:

    Route::set('GET', '/', 'ResourceController:index');
    Route::set('GET', '/create', 'ResourceController:create');
    Route::set('POST', '/', 'ResourceController:store');
    Route::set('GET', '/<id>/edit', 'ResourceController:edit');
    Route::set('GET', '/<id>', 'ResourceController:show');
    Route::set('PUT', '/<id>', 'ResourceController:update');
    Route::set('DELETE', '/<id>', 'ResourceController:destroy');
    */
});

// Group routes only HTTPS
Group::create()->secure(true)->path('/samples/routes/secure/')->then(function () {
    Route::set('GET', '/', function () {
        return '"Hello World" running on HTTPS';
    });
});

// Group routes only HTTP
Group::create()->secure(false)->path('/samples/routes/nonsecure/')->then(function () {
    Route::set('GET', '/', function () {
        return '"Hello World" running on HTTP';
    });
});

// Route patterns
Group::create()->path('/samples/routes/')->then(function () {

    Route::set('GET', '/foo/{:[^/]+:}-{:[^/]+:}', function ($param1, $param2) {
        echo '<pre>';
        print_r(array($param1, $param2));
        echo '</pre>';
    });

    // Example: http://localhost:8000/article/foo-1000
    Route::set('GET', '/article/{:[^/]+:}/{:\d+:}', function ($id, $name) {
        if (ctype_digit($id)) {
            echo 'Article ID: ', $id, '<br>';
            echo 'Article name: ', $name;
        } else {
            Response::status(400);
            echo 'Invalid URL';
        }
    });

    // Example: http://localhost:8000/blog/foo-1000
    Route::set('GET', '/blog/{:[^/]+:}-{:\d+:}', function ($id, $name) {
        echo 'Article ID: ', $id, '<br>';
        echo 'Article name: ', $name;
    });

    function testCallback($param)
    {
        echo '<h1>Results testCallback():</h1>';
        echo '<pre>';
        print_r($param);
        echo '</pre>';
    }

    Route::set('GET', '/test/{:\d+:}', 'testCallback');

    Route::set('GET', '/test/foo/{:[a-zA-Z]+:}', 'testCallback');

    Route::set('GET', '/test/bar/{:[\da-zA-Z]+:}', 'testCallback');

    Route::set('GET', '/decimal/{:(\d|[1-9]\d+)\.\d+:}', 'testCallback');
});

Group::create()->prefixNS('Samples')->path('/samples/routes/dynamic-scope-{:[a-zA-Z]+:}/')->then(function ($scopeParam) {
    Route::set('GET', '/route', function () use ($scopeParam) {
        echo '<pre>';
        echo '(from ->scope()) $scopeParam =&gt; ';
        var_dump($scopeParam);
        echo '</pre>';
    });
});

// DOM
Group::create()->path('/samples/dom/')->then(function () {
    // DOM CSS-selector
    Route::set('GET', '/css-selector', function () {
        $handle = new Document();

        $handle->loadHTML('<html><head></head><body><div x=\'abc"def\'>Hello World!</div><div id=\'foo\'>bar</div></body></html>');

        echo '<pre>';

        $elements = $handle->query('body > div');
        var_dump($elements);

        $element = $handle->first('#foo');
        var_dump($element);

        var_dump(htmlentities((string) $handle));
        echo '</pre>';
    });

    // XML to Array
    Route::set('ANY', '/to-array', function () {
        echo '<pre>';

        $handle = new Document();

        $handle->loadXML('<root xmlns:book="https://book.io"><node foo="bar" baz="foobar">contents</node><book:tag>baz</book:tag></root>');

        echo "\nCOMPLETE:\n";
        print_r($handle->toArray(Document::COMPLETE));

        echo "\nSimple:\n";
        print_r($handle->toArray(Document::SIMPLE));

        echo "\nMINIMAL:\n";
        print_r($handle->toArray(Document::MINIMAL));

        echo '</pre>';
    });

    // Array to XML
    Route::set('ANY', '/array-to-{:[^/]+:}', function ($type) {
        if ($type === 'html') {
            $handle = new Document();

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
                            '@comments' => 'test'
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
        } elseif ($type === 'xml') {
            $handle = new Document();

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
                    '@comments' => 'foobar'
                ]
            ]);
        }

        echo '<pre>';

        print_r($handle->query('.sample'));
        print_r($handle->query('node[foo=bar]'));

        var_dump(htmlentities((string) $handle));

        echo '</pre>';
    });

    // XML error
    Route::set('ANY', '/file-error', function () {
        $handle = new Document();
        $handle->load('public/error.xml', true);

        echo '<pre>';
        var_dump(htmlentities($handle->dump()));
        echo '</pre>';
    });
});

// Samples
Group::create()->path('/samples/')->then(function () {
    App::on('foobar', function ($arg1, $arg2) {
        echo "1st function: {$arg1}, {$arg2}<br>";
    });

    App::on('foobar', function ($arg1, $arg2) {
        echo "2nd function: {$arg1}, {$arg2}<br>";
    });

    App::on('foobar', function ($arg1, $arg2) {
        echo "3rd function: {$arg1}, {$arg2}<br>";

        // Stop propagation
        return false;
    }, -1);

    App::on('foobar', function ($arg1, $arg2) {
        echo "4th function: {$arg1}, {$arg2}<br>";
    }, 1);

    App::on('foobar', function ($arg1, $arg2) {
        echo "5th function: {$arg1}, {$arg2}<br>";
    });

    App::on('foobar', function ($arg1, $arg2) {
        echo "6th function: {$arg1}, {$arg2} (will not be executed due to propagation stopping at the 5th event)<br>";
    }, -1);

    // trigger event
    Route::set('ANY', '/event', function () {
        App::trigger('foobar', ['param1', microtime(true)]);
    });

    Route::set('ANY', '/config', function () {
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

    Route::set('ANY', '/file', function () {
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

    Route::set('ANY', '/session', function () {
        $session = new Session('sample');

        var_dump($session->float);
        var_dump($session->int);
        var_dump($session->octal);

        $session->float = 99.9;
        $session->int = 100;
        $session->octal = 0666;

        $session->commit(); // Save
    });

    Route::set('ANY', '/session/reset', function () {
        $session = new Session('sample');

        $session->float = null;
        $session->int = null;
        $session->octal = null;

        $session->commit(); // Save

        echo 'Reset session';
    });

    Route::set('ANY', '/session/regenerate', function () {
        $session = new Session('sample');
        $session->regenerate();
        // saves data that may not have been added yet
        $session->commit();
    });
});

// Utilities
Group::create()->path('/samples/utilities/')->then(function () {
    Route::set('GET', '/arrays', function () {

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

    Route::set('GET', '/strings', function () {
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
            echo 'ASCII: ', Helper::toAscii($str), '<hr>';
        }

        echo '<h2>Capitalize</h2>';

        echo '<pre>';
        var_dump(Helper::capitalize('foo-bar-baz'));

        var_dump(Helper::capitalize('foo bar baz', ' '));

        var_dump(Helper::capitalize('foo:bar:baz', ':', '_'));
        echo '</pre>';
    });

    Route::set('GET', '/version', function () {
        echo '<pre>';

        $version = Helper::parseVersion('1.0.0');

        var_dump($version);

        echo "\n\n";

        $version = Helper::parseVersion('1.0.0+test');

        var_dump($version);

        echo "\n\n";

        $version = Helper::parseVersion('1.b.a+test');

        var_dump($version);

        echo '</pre>';
    });

    Route::set('GET', '/url', function () {
        $path = "/foo/../--x--/--/./ã é ô ü/user@local/Ã É Ô Ü/Αλφαβητικός/섭지코지/\r\ntest\t /";
        $query = 'Z=1&B=2&C=3&Y=4';
        $str = "https://usêr:pãss@sample.io/{$path}/?{$query}#fragment";

        echo 'Original: ', $str, '<hr>';

        $url = Uri::normalize($str);
        echo '<h2>Canon URL path:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = Uri::normalize($path, Uri::ASCII);
        echo '<h2>ASCII:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = Uri::normalize($path, Uri::UNICODE);
        echo '<h2>UNICODE:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $url = Uri::normalize($path);
        echo '<h2>SLUG:</h2>', $url;

        echo '<pre>';
        var_dump($url);
        echo '</pre><hr>';

        $sorted = Uri::canonquery($query);
        echo '<h2>SORT_QUERY:</h2>', $query;

        echo '<pre>';
        var_dump($sorted);
        echo '</pre><hr>';

        $path = '/home/foo/../bar/./test.txt';

        echo '<h2>Canon path:</h2>';
        echo '<p>Before: ', $path,'</p>';

        $path = Uri::canonpath($path);
        echo '<p>After: ', $path,'</p><hr>';

        $path = 'C:\\home\\foo\\..\\bar\\.\\test.txt';

        echo '<h2>Canon path:</h2>';
        echo '<p>Before: ', $path,'</p>';

        $path = Uri::canonpath($path);
        echo '<p>After: ', $path,'</p>';
    });
});

Group::create()->path('/samples/http/')->then(function () {
    Route::set('ANY', '/cache', function () {
        View::render('home', [
            'items' => [],
            'version' => null,
            'time' => date('h:i:s')
        ]);

        Response::cache(30);
    });

    // HTTP Response download page
    Route::set('ANY', '/download', function () {
        View::render('home', [
            'items' => [],
            'version' => null,
            'time' => date('h:i:s')
        ]);

        Response::download('page.html');
    });

    // Accept headers
    Route::set('GET', '/negotiation', function () {
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

    Route::set('GET', '/negotiation/string', function () {
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

    Route::set('GET', '/negotiation/qfactor', function () {
        $entry = 'mesh/capsule;q=0.2,mesh/cube;q=0.9,mesh/cylinder;q=0.5,mesh/plane;q=0.4,mesh/quad;q=0.3,mesh/sphere;q=0.8';

        $customEntry = Negotiation::qFactor($entry, Negotiation::HIGH);

        echo '<p>Parse: <code>', $entry, '</code></p>';
        echo '<pre>';
        var_dump($customEntry);
        echo '</pre>';
    });

    Route::set('GET', '/get', function () {
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
