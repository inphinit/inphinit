<?php
use Inphinit\Experimental\Cli\Command;

/** @var Inphinit\Experimental\Cli\Console $console */

$console->action('sample', function (Command $command, array $options, array $residues) {
    echo 'You execute ', $command->getName(), ' command';
});

$console->action('hello', 'HelloCommand::index')
        ->setOption('name', 'n', Command::ARG_REQUIRED, null, 'Define a name')
        ->enableResidues(true);
