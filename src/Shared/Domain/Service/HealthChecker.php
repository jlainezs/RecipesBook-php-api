<?php
namespace App\Shared\Domain\Service;

interface HealthChecker
{
    public function isDatabaseAlive(): bool;
}
