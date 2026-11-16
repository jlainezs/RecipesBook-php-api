<?php

namespace App\Shared\Presentation\Http\Controller;

use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\HealthCheck\HealthCheckQuery;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\Resource\HealthCheckResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
final class HealthCheckController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly HealthCheckResource $resource
    ){}

    #[Route('/healthcheck', name: 'api_v1_healthcheck', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function healthcheck(): JsonApiResponse
    {
        $response = $this->queryBus->ask(new HealthCheckQuery());

        return new JsonApiResponse($this->resource->item($response->healthCheckDto));
    }
}
