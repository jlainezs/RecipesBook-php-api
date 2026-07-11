<?php
namespace App\Recipe\Application\Query\Recipe;

use App\Recipe\Application\Service\RecipeItemsPager;
use App\Recipe\Domain\Model\Recipe;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipesQueryHandler
{
    public function __construct(private RecipeItemsPager $list)
    {}

    public function __invoke(RecipesQuery $query): RecipesQueryResponse
    {
        $itemsDto = array_map(
            fn(Recipe $recipe) => new RecipeDto(
                id: $recipe->id,
                name: $recipe->name,
                servings: $recipe->servings,
                rating: $recipe->rating,
                description: $recipe->description,
                source: $recipe->source,
                steps: $recipe->steps,
                createdAt: $recipe->createdAt,
                updatedAt: $recipe->updatedAt
            ),
            $this->list->items($query->offset, $query->limit)
        );

        return new RecipesQueryResponse($itemsDto);
    }
}
