<?php
use Inphinit\Experimental\Cli\Command;
use Inphinit\Experimental\Scheduling\Task;

/** @var Inphinit\Experimental\Cli\Console $console */
/** @var Inphinit\Experimental\Scheduling\Scheduler $scheduler */

$console->action('sample', function (Command $command, array $options, array $residues) {
    echo 'You execute ', $command->getName(), ' command';
});

$hello = $console->action('hello', 'HelloCommand::index')
                 ->setOption('name', 'n', Command::ARG_REQUIRED, null, 'Define a name')
                 ->enableResidues(true);

// By default, the Scheduler uses UTC; to change, edit and uncomment the following line:
# $scheduler->setTimeZone(new \DateTimeZone('America/Sao_Paulo'));

// Schedule the "hello" command to run at 3:00 AM (UTC).
$scheduler->command('mytask', $hello, ['name' => 'Task Master!'])->cron(0, 3, '*', '*', '*', '*');
