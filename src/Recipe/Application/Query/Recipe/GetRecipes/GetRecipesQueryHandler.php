<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipes;

use App\Recipe\Application\Query\Recipe\RecipeDto;
use App\Recipe\Application\Service\RecipeItemsPager;
use App\Recipe\Domain\Model\Recipe;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetRecipesQueryHandler
{
    public function __construct(private RecipeItemsPager $list)
    {}

    public function __invoke(GetRecipesQuery $query): GetRecipesQueryResponse
    {
        $itemsDto = array_map(
            fn(Recipe $recipe) => new RecipeDto(
                id: $recipe->getId()->toString(),
                name: $recipe->getName(),
                servings: $recipe->getServings()->value(),
                rating: $recipe->getRating()->value(),
                description: $recipe->getDescription(),
                source: $recipe->getSource(),
                steps: $recipe->getSteps(),
                ingredients: $recipe->getIngredients(),
                createdAt: $recipe->getCreatedAt(),
                updatedAt: $recipe->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );

        return new GetRecipesQueryResponse($itemsDto);
    }
}
