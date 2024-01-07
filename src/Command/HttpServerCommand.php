<?php

namespace App\Command;

use App\Server\Http\HttpServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class HttpServerCommand extends Command
{
    // todo:
    // add response factory
    // static date response factory should serve .html static files
    // dynamic response factory
    // dynamic response factory should load modules for every language
    // each module will know how to parse and retrieve response message from index file, like index.php
    public const COMMAND_NAME = 'http-server';

    private const OPTION_HOST = 'host';
    private const ARG_DOC_ROOT = 'document_root';

    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Starts the HTTP server')
            ->addArgument(self::ARG_DOC_ROOT, InputArgument::OPTIONAL, 'HTML/PHP document root file path', './index.html')
            ->addOption(self::OPTION_HOST, null, InputOption::VALUE_OPTIONAL, 'Specify the host (default: 127.0.0.1:8000)', '127.0.0.1:8000')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $host = $input->getOption(self::OPTION_HOST);
        $documentRoot = $input->getArgument(self::ARG_DOC_ROOT);

        if (false === str_contains($host, ':')) {
            $output->writeln('Host must contain port separated by colon (:)');

            return Command::FAILURE;
        }

        $output->writeln("Starting HTTP server on {$host}");

        $server = new HttpServer(
            ...explode(':', $host)
        );

        $server->handleRequests($documentRoot);
    }
}
