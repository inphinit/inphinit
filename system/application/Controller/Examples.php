<?php
namespace Controller;

use Inphinit\App;

class Examples
{
    public function info()
    {
        App::on('ready', 'phpinfo');
    }

    public function eventReady()
    {
        $args = [
            'foo' => 'Foo',
            'bar' => 'bar',
            'baz' => 'baz'
        ];

        App::on('ready', function () use ($args) {
            print_r($args);
        });
    }
}
