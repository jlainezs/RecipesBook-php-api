<?php
namespace App\Season\Presentation\Http\Resource;

use App\Season\Application\Query\Season\SeasonDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class SeasonResource
{
    public const string TYPE = 'season';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function selfUrl(string $id): string
    {
        return $this->urlGenerator->generate('seasons_get_instance', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate(
            'seasons_list',
            ['page' => ['offset' => $offset, 'limit' => $limit]],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    public function resourceObject(SeasonDto $seasonDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => $seasonDto->id,
            'attributes' => [
                'name' => $seasonDto->name,
                'createdAt' => $seasonDto->createdAt,
                'updatedAt' => $seasonDto->updatedAt,
            ],
            'links' => [
                'self' => $this->selfUrl($seasonDto->id),
            ],
        ];
    }

    public function collection(array $items, int $offset, int $limit): array
    {
        return [
            'links' => [
                'self' => $this->listUrl($offset, $limit),
                'first' => $this->listUrl(0, $limit),
                'prev' => $offset > 0 ? $this->listUrl(max(0, $offset - $limit), $limit) : null,
                'next' => $limit > 0 && count($items) === $limit ? $this->listUrl($offset + $limit, $limit) : null,
            ],
            'data' => array_map($this->resourceObject(...), $items)
        ];
    }

    public function item(SeasonDto $itemDto): array
    {
        return [
            'links' => [
                'self' => $this->selfUrl($itemDto->id),
            ],
            'data' => $this->resourceObject($itemDto),
        ];
    }

    public function itemReference(string $id): array
    {
        return [
            'links' => ['self' => $this->selfUrl($id)],
            'data' => [
                'type' => self::TYPE,
                'id' => $id,
                'links' => ['self' => $this->selfUrl($id)],
                'attributes' => [
                    'id' => $id,
                ]
            ],
        ];
    }
}
