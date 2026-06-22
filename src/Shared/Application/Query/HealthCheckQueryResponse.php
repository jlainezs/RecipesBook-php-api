<?php
namespace App\Shared\Application\Query;
final readonly class HealthCheckQueryResponse
{
    public function __construct(public readonly bool $isDatabaseAlive)
    {}
}
