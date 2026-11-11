<?php
namespace App\Recipe\Presentation\Http\Resource;

use App\Recipe\Application\Query\Recipe\RecipeDto;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class RecipeResource
{
    public const string TYPE = 'recipe';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {}

    public function item(RecipeDto $recipe): array
    {
        return [
            'links' => ['self' => $this->selfUrl($recipe->id)],
            'data' => $this->resourceObject($recipe),
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
            'data' => array_map($this->resourceObject(...), $items),
        ];
    }

    public function resourceObject(RecipeDto $recipe): array
    {
        return [
            'type' => self::TYPE,
            'id' => $recipe->id,
            'attributes' => [
                'name' => $recipe->name,
                'description' => $recipe->description,
                'servings' => $recipe->servings,
                'rating' => $recipe->rating,
                'source' => $recipe->source,
                'ingredients' => $recipe->ingredients,
                'steps' => $recipe->steps,
                'createdAt' => $recipe->createdAt,
                'updatedAt' => $recipe->updatedAt,
            ],
            'links' => [
                'self' => $this->selfUrl($recipe->id),
            ],
        ];
    }

    public function selfUrl(string $id): string
    {
        return $this->urlGenerator->generate('get_recipe', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function listUrl(int $offset, int $limit): string
    {
        return $this->urlGenerator->generate(
            'get_recipes_list',
            ['offset' => $offset, 'limit' => $limit],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
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
                    'id' => $id
                ]
            ]
        ];
    }
}
