<?php

require __DIR__.'/vendor/autoload.php';

use App\Command\HttpServerCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

set_time_limit(0);
ob_implicit_flush();

// Create the Symfony Console application
$application = new Application();

// Add your custom command to the application
$application->add(new HttpServerCommand());

// Create instances of InputInterface and OutputInterface
$input = new ArgvInput();
$output = new ConsoleOutput();

// Run the application
$application->find(HttpServerCommand::COMMAND_NAME)->run($input, $output);
