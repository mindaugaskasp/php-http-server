<?php

use Src\Connection\Tcp\TcpSocketConnection;

require __DIR__.'/vendor/autoload.php';

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
$tcpServer = new TcpSocketConnection(
    ...explode(':', $host)
);

$tcpServer->listen();

echo sprintf('Listening on %s', $host);

// todo remove this later
$response = "HTTP/2.0 200 OK\r\n";
$response .= "Content-Type: text/html\r\n";
$response .= "Host: 127.0.0.1\r\n";
$response .= "Connection: Close\r\n\r\n";
$response .= "<h1>hello,world</h1>\r\n";

while (true) {
    $connection = $tcpServer->accept();

    try {
        $data = $tcpServer->read($connection, 2046);

        echo sprintf('%s: Request received [%s]', time(), trim($data));

        $tcpServer->write($connection, $response, strlen($response));
        $tcpServer->close($connection);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
