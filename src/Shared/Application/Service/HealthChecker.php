<?php
namespace App\Shared\Application\Service;

interface HealthChecker
{
    public function isDatabaseAlive(): bool;
}
