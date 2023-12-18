<?php
set_time_limit(0);
ob_implicit_flush();

class Server {

    private Socket $socket;

    public function __construct(private string $host, private string $port)
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($this->socket, $this->host, (int) $this->port);
    }

    /**
     * @throws Exception
     */
    public function listen(): self
    {
        socket_listen($this->socket);

        return $this;
    }

    public function accept()
    {
        return socket_accept($this->socket);
    }

    /**
     * @throws Exception
     */
    public function read(Socket $connection): string
    {
        // read any data coming from established tcp socket connection
        $data = socket_read($connection, 2048);

        if (false === $data) {
            throw new Exception('Failed to read incoming tcp connection data');
        }

        return $data;
    }
}

$args = $_SERVER['argv'];

$host = '127.0.0.1';
$port = '8000';

$argArray = [];

// try to parse command line arguments for --host and --port

if (count($args) > 1) {
    parse_str($args[1], $argArray);
}

if (count($args) > 2) {
    parse_str($args[1], $argArray);
    parse_str($args[2], $argArray);
}

// overwrite with command line arguments if any
$host = $argArray['--host'] ?? $host;
$port = $argArray['--port'] ?? $port;

// Run things
$server = new Server($host, $port);

$message = sprintf('Listening on %s:%s', $host, $port);

$server->listen();

$response = "HTTP/2.0 500 Internal Error\r\n";
$response .= "Content-Type: application/json\r\n";
$response .= "Host: 127.0.0.1\r\n";
$response .= "Connection: Close\r\n\r\n";

do {
    $tcp = $server->accept();
    if (false === $tcp) {
        break;
    }

    try {
        $data = $server->read($tcp);

        echo sprintf('%s: Request received [%s]', time(), trim($data));

        socket_write($tcp, $response);

        socket_close($tcp);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} while (true);


