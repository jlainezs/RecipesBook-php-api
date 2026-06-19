<?php

namespace App\HealthCheck\Infrastructure\Http\Response;

class HealthCheckResponse
{
    public string $status;
    public function __construct(string $status)
    {
        $this->status = $status;
    }
}
