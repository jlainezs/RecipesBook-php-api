<?php

namespace App\Shared\Infrastructure\Persistence;

use App\Shared\Domain\Service\HealthChecker;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class DoctrineHealthChecker implements HealthChecker
{
    public function __construct(private Connection $connection, private LoggerInterface $logger)
    {
    }

    public function isDatabaseAlive(): bool
    {
        try {
            $this->connection->executeQuery($this->connection->getDatabasePlatform()->getDummySelectSQL());
            $this->logger->info('Database is alive');

            return true;
        } catch (Throwable $e) {
            $this->logger->critical('Database appears to be offline',
                ['exception' => $e]
            );

            return false;
        }
    }
}
