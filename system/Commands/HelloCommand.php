<?php
namespace Commands;

use Inphinit\Packages;
use Inphinit\Viewing\View;

class HelloCommand
{
    /**
     * A example
     *
     * @param \Inphinit\Experimental\Cli\Command $command
     * @param array $params
     * @param array $residual
     */
    public function index($command, $params, $residual)
    {
        $name = $command->getName();

        echo "{$name}\n";
        echo str_repeat('=', strlen($name)), "\n";

        echo "\nParams:\n";

        foreach ($params as $key => $value) {
            echo "{$key} => {$value}\n";
        }

        echo "\n---------\n";
        echo "Residual:\n";

        foreach ($residual as $key => $value) {
            echo "{$key} => {$value}\n";
        }

        echo "\n";
    }
}
