<?php
namespace Commands;

use Inphinit\Viewing\View;
use Inphinit\Experimental\Cli\Command;

class HelloCommand
{
    /**
     * A example
     *
     * @param \Inphinit\Experimental\Cli\Command $command
     * @param array $params
     * @param array $residual
     */
    public function index(Command $command, array $params, array $residual)
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
