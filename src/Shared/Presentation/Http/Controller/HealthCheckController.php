<?php

namespace App\Shared\Presentation\Http\Controller;

use App\Shared\Application\Service\HealthChecker;
use App\Shared\Presentation\Http\Response\HealthCheckJsonResponse;
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
