<?php

require __DIR__.'/vendor/autoload.php';

use Src\Server\Http\HttpServer;

set_time_limit(0);
ob_implicit_flush();

$args = $_SERVER['argv'];

$parsedArgs = [];

if (count($args) > 1) {
    parse_str($args[1], $parsedArgs);
}

// overwrite with command line arguments if any
$defaultHost = '127.0.0.1:8000';

$host = $parsedArgs['--host'] ?? $defaultHost;

// Run things
$server = new HttpServer(
    ...explode(':', $host)
);

$server->handleRequests();
