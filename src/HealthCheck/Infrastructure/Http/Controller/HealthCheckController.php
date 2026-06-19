<?php

namespace App\HealthCheck\Infrastructure\Http\Controller;

use App\HealthCheck\Infrastructure\Http\Response\HealthCheckResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class HealthCheckController extends AbstractController
{
    #[Route('/healthcheck', name: 'api_v1_healthcheck', methods: ['GET'])]
    public function healthcheck(): JsonResponse
    {
        return $this->json(new HealthCheckResponse('ok'));
    }
}
