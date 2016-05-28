<?php
namespace Controller;

use Inphinit\App;

class Examples
{
    public function info()
    {
        App::on('ready', 'phpinfo');
    }

    public function arrayArgs()
    {
        $args = array(
            'foo' => 'Foo',
            'bar' => 'bar',
            'baz' => 'baz'
        );

        App::on('ready', 'print_r', array($args));
    }

    public function arrayClosure()
    {
        $args = array(
            'foo' => 'Foo',
            'bar' => 'bar',
            'baz' => 'baz'
        );

        App::on('ready', function ($args) {
            print_r($args);
        }, array($args));
    }
}
