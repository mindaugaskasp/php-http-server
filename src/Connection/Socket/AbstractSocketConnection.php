<?php

declare(strict_types=1);

namespace Src\Connection\Socket;

use Src\Connection\Socket\Exception\SocketReadException;
use Src\Connection\Socket\Exception\SocketWriteException;

abstract class AbstractSocketConnection implements SocketHandler
{
    private \Socket $socket;

    public function __construct()
    {
        $this->socket = socket_create(
            $this->getDomain(),
            $this->getType(),
            $this->getProtocol()
        );
    }

    abstract public function getDomain(): int;

    abstract public function getProtocol(): int;

    abstract public function getType(): int;

    abstract public function getAddress(): string;

    abstract public function getPort(): int;

    public function bind(string $address, int $port): void
    {
        socket_bind($this->getSocket(), $address, $port);
    }

    public function listen(): bool
    {
        return socket_listen($this->getSocket());
    }

    public function getSocket(): \Socket
    {
        return $this->socket;
    }

    /**
     * @return \Socket accepted connection socket
     */
    public function accept(): \Socket
    {
        return socket_accept($this->getSocket());
    }

    /**
     * @throws SocketReadException
     */
    public function read(\Socket $connection, int $length, int $mode = PHP_BINARY_READ): string
    {
        $data = socket_read($connection, $length, $mode);

        if (false === $data) {
            throw new SocketReadException();
        }

        return $data;
    }

    /**
     * @throws SocketWriteException
     */
    public function write(\Socket $connection, string $data, ?int $length = null): int
    {
        $bytesWritten = socket_write($connection, $data, $length);

        if (false === $bytesWritten) {
            throw new SocketWriteException();
        }

        return $bytesWritten;
    }

    public function close(\Socket $connection): void
    {
        socket_close($connection);
    }
}
