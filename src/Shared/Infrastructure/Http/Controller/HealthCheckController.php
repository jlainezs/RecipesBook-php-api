<?php

namespace App\Shared\Infrastructure\Http\Controller;

use App\Shared\Domain\Service\HealthChecker;
use App\Shared\Infrastructure\Http\Response\HealthCheckJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class HealthCheckController extends AbstractController
{
    public function __construct(private readonly HealthChecker $healthChecker)
    {
    }

    #[Route('/healthcheck', name: 'api_v1_healthcheck', methods: ['GET'])]
    public function healthcheck(): JsonResponse
    {
        return HealthCheckJsonResponse::create($this->healthChecker->isDatabaseAlive());
    }
}
