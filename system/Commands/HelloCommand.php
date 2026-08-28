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
     * @param array $options
     * @param array $residues
     */
    public function index(Command $command, array $options, array $residues)
    {
        $name = $command->getName();

        echo "{$name}\n";
        echo str_repeat('=', strlen($name)), "\n";

        echo "\nOptions:\n";

        foreach ($options as $key => $value) {
            echo "{$key} => {$value}\n";
        }

        echo "\n---------\n";
        echo "Residual:\n";

        foreach ($residues as $key => $value) {
            echo "{$key} => {$value}\n";
        }

        echo "\n";
    }
}
