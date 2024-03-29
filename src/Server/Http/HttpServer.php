<?php

declare(strict_types=1);

namespace App\Server\Http;

use App\Connection\Socket\Tcp\TcpSocketConnection;
use App\Server\HttpServerHandler;
use Exception;

class HttpServer implements HttpServerHandler
{
    private TcpSocketConnection $connection;

    public function __construct(string $address, int $port)
    {
        $this->connection = new TcpSocketConnection($address, $port);
    }

    public function handleRequests(string $documentRootPath): void
    {
        $this->listen();

        while (true) {
            $connection = $this->connection->accept();

            try {
                $data = $this->connection->read($connection, 2046);

                // todo: replace this with logger for cli/log file
                echo sprintf('%s: Request received [%s]', time(), trim($data));

                $response = $this->getResponse($documentRootPath);

                $this->connection->write($connection, $response, strlen($response));
            } catch (Exception $e) {
                echo $e->getMessage();
            } finally {
                $this->connection->close($connection);
            }
        }
    }

    // todo: mark this method as abstract in separate class
    public function getResponse(string $documentRoot): string
    {
        $data = file_get_contents(realpath("./example/index.html"));

        // todo remove this later and fetch response content dynamically
        $response = "HTTP/2.0 200 OK\r\n";
        $response .= "Content-Type: text/html\r\n";
        $response .= "Host: 127.0.0.1\r\n";
        $response .= "Connection: Close\r\n\r\n";
        $response .= "$data\r\n";

        return $response;
    }

    private function listen(): void
    {
        $this->connection->listen();

        echo sprintf(
            'Listening on %s:%s',
            $this->connection->getAddress(),
            $this->connection->getPort()
        );
    }
}
