<?php
namespace App\Recipe\Application\Command\RecipeUpdate;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Domain\Model\RecipeIngredient;
use App\Recipe\Domain\Model\RecipeStep;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Recipe\Domain\ValueObjects\UnitOfMeasureReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeUpdateCommandHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
        private IngredientRepositoryInterface $ingredientRepository,
        private UnitOfMeasureRepositoryInterface $unitOfMeasureRepository,
        private LoggerInterface $logger,
    ){}

    /**
     * @throws RecipeNotFoundException
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     * @throws IngredientNotFoundException
     * @throws UnitOfMeasureNotFoundException
     * @throws Exception
     */
    public function __invoke(RecipeUpdateCommand $command): void
    {
        if ($recipe = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $steps = [];
            foreach ($command->steps as $step){
                if (!empty($step->id)) {
                    if ($stepObject = $recipe->getStep($step->id))
                    {
                        $stepObject->setDescription($step->description);
                        $stepObject->reorder($step->ordering);
                    }
                    else
                    {
                        $this->logger->warning(
                            sprintf("Required recipe step %s not found in recipe %s", $step->id, $recipe->getId()->toString())
                        );
                        throw new Exception('Step not found ' . $step->id);
                    }
                } else {
                    $stepObject = RecipeStep::create(
                        recipe: $recipe,
                        ordering: $step->ordering,
                        description: $step->description
                    );
                }
                $steps[] = $stepObject;
            }

            $ingredients = [];
            foreach ($command->ingredients as $recipeIngredient){
                $ingredientId = new AggregateRootId($recipeIngredient->ingredientId);
                $unitOfMeasureId = new AggregateRootId($recipeIngredient->unitOfMeasureId);

                $ingredient = $this->ingredientRepository->findOne($ingredientId);
                if (null === $ingredient) {
                    throw new IngredientNotFoundException($ingredientId->toString());
                }

                $unitOfMeasure = $this->unitOfMeasureRepository->findOne($unitOfMeasureId);
                if (null === $unitOfMeasure) {
                    throw new UnitOfMeasureNotFoundException($unitOfMeasureId->toString());
                }

                $ingredientReference = new IngredientReference($ingredient->getId()->toString());
                $unitOfMeasureReference = new UnitOfMeasureReference($unitOfMeasure->getId()->toString());

                if (isset($recipeIngredient->id)) {
                    if ($ingredientObject = $recipe->getIngredient($recipeIngredient->id))
                    {
                        $ingredientObject->setQuantity($recipeIngredient->quantity);
                        $ingredientObject->reorder($recipeIngredient->ordering);
                        $ingredientObject->setUnitOfMeasure($unitOfMeasureReference);
                        $ingredientObject->setIngredient($ingredientReference);
                    }
                    else
                    {
                        $this->logger->warning(
                            sprintf("Required recipe ingredient %s not found in recipe %s", $recipeIngredient->id, $recipe->getId()->toString())
                        );
                        throw new Exception('Recipe ingredient not found ' . $recipeIngredient->id);
                    }
                } else {
                    $ingredientObject = RecipeIngredient::create(
                        recipe: $recipe,
                        ingredient: $ingredientReference,
                        unitOfMeasure: $unitOfMeasureReference,
                        quantity: $recipeIngredient->quantity,
                        ordering: $recipeIngredient->ordering
                    );
                }
                $ingredients[] = $ingredientObject;
            }

            $recipe->rename($command->name);
            $recipe->setDescription($command->description);
            $recipe->setSource($command->source);
            $recipe->setServings($command->servings);
            $recipe->setRating($command->rating);
            $recipe->setSteps($steps);
            $recipe->setIngredients($ingredients);
            $this->repository->save($recipe);
        }
        else
        {
            throw new RecipeNotFoundException($command->id);
        }
    }
}
