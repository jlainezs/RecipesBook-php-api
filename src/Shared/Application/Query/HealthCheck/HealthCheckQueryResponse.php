<?php
namespace App\Shared\Application\Query\HealthCheck;
final readonly class HealthCheckQueryResponse
{
    public function __construct(public HealthCheckDto $healthCheckDto)
    {}
}
