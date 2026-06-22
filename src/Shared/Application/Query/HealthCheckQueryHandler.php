<?php
namespace App\Shared\Application\Query;

use App\Shared\Application\Service\HealthChecker;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class HealthCheckQueryHandler
{
    public function __construct(private HealthChecker $healthChecker) {}
    public function __invoke(HealthCheckQuery $query): HealthCheckQueryResponse
    {
        return new HealthCheckQueryResponse($this->healthChecker->isDatabaseAlive());
    }
}
