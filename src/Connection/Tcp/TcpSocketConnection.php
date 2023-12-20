<?php

declare(strict_types=1);

namespace Src\Connection\Tcp;

use Src\Socket\Client\AbstractSocketConnection;

class TcpSocketConnection extends AbstractSocketConnection
{
    public function __construct(private readonly string $address, private readonly int $port)
    {
        parent::__construct();
        $this->bind($this->address, $this->port);
    }

    public function getDomain(): int
    {
        return AF_INET;
    }

    public function getProtocol(): int
    {
        return SOL_TCP;
    }

    public function getType(): int
    {
        return SOCK_STREAM;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
