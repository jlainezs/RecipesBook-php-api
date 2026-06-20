<?php

namespace App\Shared\Infrastructure\Http\Controller;

use App\Shared\Domain\Service\HealthChecker;
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
        $dbStatus = $this->healthChecker->isDatabaseAlive();

        return new JsonResponse([
            'DB' => $dbStatus
                ? 'Up'
                : 'Down',
        ]);
    }
}
