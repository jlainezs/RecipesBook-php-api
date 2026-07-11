<?php
namespace App\Recipe\Application\Command\Recipe;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Model\Recipe;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Domain\Repository\RecipeStepRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeCreateCommandHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository,
        private RecipeStepRepositoryInterface $recipeStepRepository
    ){}

    /**
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(RecipeCreateCommand $command): void
    {
        $recipe = Recipe::create(
            $command->name,
            $command->servings,
            $command->rating,
            $command->description,
            $command->source,
            $command->steps
        );
        $this->repository->save($recipe);
        //$this->recipeStepRepository->save($recipe, $recipe->getSteps());
    }
}
