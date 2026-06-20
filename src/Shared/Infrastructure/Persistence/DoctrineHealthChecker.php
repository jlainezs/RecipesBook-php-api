<?php

namespace App\Shared\Infrastructure\Persistence;

use App\Shared\Domain\Service\HealthChecker;
use Doctrine\DBAL\Connection;

final readonly class DoctrineHealthChecker implements HealthChecker
{
    public function __construct(private Connection $connection)
    {
    }

    public function isDatabaseAlive(): bool
    {
        try {
            $this->connection->executeQuery($this->connection->getDatabasePlatform()->getDummySelectSQL());
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
