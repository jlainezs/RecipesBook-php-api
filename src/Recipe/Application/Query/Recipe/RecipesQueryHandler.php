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
                id: $recipe->getId()->toString(),
                name: $recipe->getName(),
                servings: $recipe->getServings(),
                rating: $recipe->getRating(),
                description: $recipe->getDescription(),
                source: $recipe->getSource(),
                steps: $recipe->getSteps(),
                createdAt: $recipe->getCreatedAt(),
                updatedAt: $recipe->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );

        return new RecipesQueryResponse($itemsDto);
    }
}
