<?php

declare(strict_types=1);

namespace Src\Socket;

use Socket;

interface SocketHandler
{
    public function getSocket(): \Socket;

    public function bind(string $address, int $port): void;

    public function listen(): bool;

    public function accept(): \Socket;

    public function read(\Socket $connection, int $length, int $mode = PHP_BINARY_READ): string;

    /**
     * @return int bytes written to socket
     */
    public function write(\Socket $connection, string $data, ?int $length = null): int;

    public function close(\Socket $connection): void;
}
