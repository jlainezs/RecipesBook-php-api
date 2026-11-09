<?php
namespace App\IngredientType\Presentation\Http\Resource;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class IngredientTypeResource
{
    public const string TYPE = 'ingredient_type';
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function selfUrl(string $id): string
    {
        return $this->urlGenerator->generate('ingredient_get_instance', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate(
            name: 'ingredient_list',
            parameters: ['offset' => $offset, 'limit' => $limit],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL);
    }

    private function resourceObject(IngredientTypeDto $ingredientTypeDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => $ingredientTypeDto->id,
            'attributes' => [
                'name' => $ingredientTypeDto->name,
                'createdAt' => $ingredientTypeDto->createdAt->format(DATE_ATOM),
                'updatedAt' => $ingredientTypeDto->updatedAt->format(DATE_ATOM),
            ],
            'links' => [
                'self' => $this->selfUrl($ingredientTypeDto->id),
            ]
        ];
    }

    public function collection(array $items, int $offset, int $limit): array
    {
        return [
            'links' => [
                'self' => $this->listUrl($offset, $limit),
                'first' => $this->listUrl(0, $limit),
                'prev' => $offset >0 ? $this->listUrl(max(0, $offset - $limit), $limit) : null,
                'next' => $limit > 0 && count($items) === $limit ? $this->listUrl($offset + $limit, $limit) : null,
            ],
            'data' => array_map($this->resourceObject(...), $items),
        ];
    }

    public function item(IngredientTypeDto $ingredientTypeDto): array
    {
        return [
            'links' => [
                 'self' => $this->selfUrl($ingredientTypeDto->id),
            ],
            'data' => $this->resourceObject($ingredientTypeDto),
        ];
    }

    public function itemReference(string $id): array
    {
        return [
            'links' => [
                'self' => $this->selfUrl($id),
            ],
            'data' => [
                'type' => self::TYPE,
                'id' => $id,
                'links' => [
                    'self' => $this->selfUrl($id),
                ],
                'attributes' => [
                    'id' => $id,
                ],
            ],
        ];
    }
}
