<?php
namespace App\Shared\Application\Query\HealthCheck;

readonly final class HealthCheckDto
{
    public function __construct(
        public bool $databaseOk
    ){}
}
