<?php

namespace App\Ingredient\Presentation\Http\Resoource;

use App\Ingredient\Application\Query\Ingredient\IngredientDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class IngredientResource
{
    public const string TYPE = 'ingredient';
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
            parameters: ['page' => ['offset' => $offset, 'limit' => $limit]],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    private function resourceObject(IngredientDto $ingredientDto): array
    {
        return [
            'type' => self::TYPE,
            'id' => $ingredientDto->id,
            'attributes' => [
                'name' => $ingredientDto->name,
                'description' => $ingredientDto->description,
                'ingredientTypeId' => $ingredientDto->ingredientTypeId,
                'createdAt' => $ingredientDto->createdAt->format(DATE_ATOM),
                'updatedAt' => $ingredientDto->updatedAt->format(DATE_ATOM),
            ],
            'links' => ['self' => $this->selfUrl($ingredientDto->id)],
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

    public function item(IngredientDto $ingredientDto): array
    {
        return [
            'links' => ['self' => $this->selfUrl($ingredientDto->id)],
            'data' => $this->resourceObject($ingredientDto),
        ];
    }
}
