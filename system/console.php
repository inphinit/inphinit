<?php
use Inphinit\Experimental\Cli\Command;

/** @var Inphinit\Experimental\Cli\Console $console */

$console->action('sample', function (Command $command, array $params, array $residual) {
    echo 'You execute ', $command->getName(), ' command';
});

$console->action('hello', 'HelloCommand::index')
        ->setOption('name', 'n', Command::ARG_REQUIRED, null, 'Define a name')
        ->enableResidual(true);
