<?php
use Inphinit\App;
use Inphinit\Experimental\Cli\Command;

$console->action('sample', function ($command, $params, $residual) {
    echo 'You execute ', $command->getName(), ' command';
});

$console->action('hello', 'HelloCommand::index')
        ->setOption('name', 'n', Command::OPT_REQUIRED, null, 'Define a name')
        ->enableResidual(true);
