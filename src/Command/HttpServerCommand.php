<?php

namespace App\Command;

use App\Server\Http\HttpServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HttpServerCommand extends Command
{
    public const COMMAND_NAME = 'http-sercer';

    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Starts the HTTP server')
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Specify the host (default: 127.0.0.1:8000)', '127.0.0.1:8000')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption('host');

        $output->writeln("Starting HTTP server on {$host}");

        $server = new HttpServer(
            ...explode(':', $host)
        );

        $server->handleRequests();
    }
}
