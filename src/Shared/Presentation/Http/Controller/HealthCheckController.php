<?php

namespace App\Shared\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\HealthCheck\HealthCheckQuery;
use App\Shared\Presentation\Http\Response\HealthCheckJsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class HealthCheckController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    #[Route('/healthcheck', name: 'api_v1_healthcheck', methods: ['GET'])]
    public function healthcheck(): JsonResponse
    {
        $response = $this->queryBus->ask(new HealthCheckQuery());
        return HealthCheckJsonResponse::create($response->isDatabaseAlive);
    }
}
