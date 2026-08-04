<?php
namespace App\Recipe\Application\Command\RecipeCreate;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Model\RecipeIngredient;
use App\Recipe\Domain\Model\RecipeStep;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Recipe\Domain\ValueObjects\UnitOfMeasureReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\Ordering;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
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
     * @throws IngredientNotFoundException
     * @throws UnitOfMeasureNotFoundException
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
            $ingredientId = new AggregateRootId($ingredientData['ingredientId']);
            $unitOfMeasureId = new AggregateRootId($ingredientData['unitOfMeasureId']);

            $ingredient = $this->ingredientRepository->findOne($ingredientId);
            if (null === $ingredient) {
                throw new IngredientNotFoundException($ingredientId->toString());
            }

            $unitOfMeasure = $this->unitOfMeasureRepository->findOne($unitOfMeasureId);
            if (null === $unitOfMeasure) {
                throw new UnitOfMeasureNotFoundException($unitOfMeasureId->toString());
            }

            $ingredients[] = RecipeIngredient::create(
                recipe: $recipe,
                ingredient: new IngredientReference($ingredient->getId()->toString()),
                unitOfMeasure: new UnitOfMeasureReference($unitOfMeasure->getId()->toString()),
                quantity: $ingredientData['quantity'],
                ordering: $ingredientData['ordering'],
            );
        }
        $recipe->setSteps($steps);
        $recipe->setIngredients($ingredients);
        $this->repository->save($recipe);
    }
}
