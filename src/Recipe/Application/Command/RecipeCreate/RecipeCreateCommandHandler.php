<?php
namespace App\Recipe\Application\Command\RecipeCreate;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Model\RecipeIngredient;
use App\Recipe\Domain\Model\RecipeStep;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeCreateCommandHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
        private IngredientRepositoryInterface $ingredientRepository,
        private UnitOfMeasureRepositoryInterface $unitOfMeasureRepository,
    ){}

    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(RecipeCreateCommand $command): void
    {
        $ingredients = [];
        $steps = [];

        $recipe = Recipe::create(
            $command->name,
            $command->servings,
            $command->rating,
            $command->description,
            $command->source,
            [],
            [],
        );

        foreach ($command->steps as $stepData) {
            $steps[] = RecipeStep::create(
                recipe: $recipe,
                ordering: $stepData['ordering'],
                description: $stepData['description'],
            );
        }

        foreach ($command->ingredients as $ingredientData) {
            $ingredient = $this->ingredientRepository->find($ingredientData['ingredientId']);
            $unitOfMeasure = $this->unitOfMeasureRepository->find($ingredientData['unitOfMeasureId']);
            $ingredients[] = RecipeIngredient::create(
                recipe: $recipe,
                ingredient: $ingredient,
                unitOfMeasure: $unitOfMeasure,
                quantity: $ingredientData['quantity'],
                ordering: $ingredientData['ordering'],
            );
        }
        $recipe->setSteps($steps);
        $recipe->setIngredients($ingredients);
        $this->repository->save($recipe);
    }
}
