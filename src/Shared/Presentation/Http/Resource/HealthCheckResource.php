<?php
namespace App\Shared\Presentation\Http\Resource;

use App\Shared\Application\Query\HealthCheck\HealthCheckDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class HealthCheckResource
{
    public const string TYPE = 'health-check';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function selfUrl(): string
    {
        return $this->urlGenerator->generate('api_v1_healthcheck');
    }

    public function listUrl(): string
    {
        return $this->urlGenerator->generate('api_v1_healthcheck');
    }

    public function resourceObject(HealthCheckDto $healthCheckDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => '',
            'attributes' => [
                'databaseOk' => $healthCheckDto->databaseOk,
            ],
            'links' => ['self' => $this->selfUrl()],
        ];
    }

    public function collection(array $items, int $offset, int $limit): array
    {
        return [
            'links' => [
                'self' => $this->listUrl(),
                'first' => $this->listUrl(),
                'next' => $this->listUrl(),
                'prev' => $this->listUrl(),
            ],
            'data' => [$this->resourceObject($items[0])],
        ];
    }

    public function item(HealthCheckDto $healthCheckDto): array
    {
        return [
            'links' => ['self' => $this->selfUrl()],
            'data' => $this->resourceObject($healthCheckDto),
        ];
    }
}
