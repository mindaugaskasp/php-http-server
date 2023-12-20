<?php

namespace Src\Server\Http;

use Src\Connection\Tcp\TcpSocketConnection;
use Src\Server\HttpServerHandler;

class HttpServer implements HttpServerHandler
{
    private TcpSocketConnection $connection;

    public function __construct(string $address, int $port)
    {
        $this->connection = new TcpSocketConnection($address, $port);
    }
}
