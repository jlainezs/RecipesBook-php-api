<?php
namespace App\Recipe\Application\Query\Recipe;

use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Infrastructure\Repository\RecipeRepository;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeInstanceQueryHandler
{
    public function __construct(private RecipeRepository $repository)
    {}

    /**
     * @throws RecipeNotFoundException
     */
    public function __invoke(RecipeInstanceQuery $query): RecipeInstanceResponse
    {
        if ($recipe = $this->repository->findOne(new AggregateRootId($query->id)))
        {
            $mapped_steps = [];

            foreach ($recipe->getSteps() as $step)
            {
                $mapped_steps[] = new RecipeStepDto(
                    id: $step->getId()->toString(),
                    description: $step->getDescription(),
                    ordering: $step->getOrdering()->value(),
                    createdAt: $step->getCreatedAt(),
                    updatedAt: $step->getUpdatedAt(),
                );
            }

            $mapped_ingredients = [];
            foreach ($recipe->getIngredients() as $ingredient)
            {
                $mapped_ingredients[] = new RecipeIngredientDto(
                    id: $ingredient->getId()->toString(),
                    recipeId: $recipe->getId()->toString(),
                    ingredientId: $ingredient->getIngredient()->value()->toString(),
                    unitOfMeasureId: $ingredient->getUnitOfMeasure()->value()->toString(),
                    ordering: $ingredient->getOrdering()->value(),
                    quantity: $ingredient->getQuantity()->value(),
                    createdAt: $ingredient->getCreatedAt(),
                    updatedAt: $ingredient->getUpdatedAt()
                );
            }

            return new RecipeInstanceResponse(new RecipeDto(
                id: $recipe->getId()->toString(),
                name: $recipe->getName(),
                servings: $recipe->getServings()->value(),
                rating: $recipe->getRating()->value(),
                description: $recipe->getDescription(),
                source: $recipe->getSource(),
                steps: $mapped_steps,
                ingredients: $mapped_ingredients,
                createdAt: $recipe->getCreatedAt(),
                updatedAt: $recipe->getUpdatedAt()
            ));
        }

        throw new RecipeNotFoundException($query->id);
    }
}
