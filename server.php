<?php

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
    public function listen(): Socket
    {
        socket_listen($this->socket);
        return socket_accept($this->socket);
    }

    /**
     * @throws Exception
     */
    public function read(Socket $connection): string
    {
        // read any data coming from established tcp socket connection
        $data = socket_read($connection, 1024);

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


while (true) {
    $tcp = $server->listen();

    try {
        $data = $server->read($tcp);

        if (strlen($data) > 0) {
            echo sprintf('%s: Request received [%s]', time(), $data);

            socket_write($tcp, $data);
        }

        socket_close($tcp);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


