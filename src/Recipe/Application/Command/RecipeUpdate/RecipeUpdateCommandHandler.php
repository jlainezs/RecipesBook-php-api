<?php
namespace App\Recipe\Application\Command\RecipeUpdate;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeUpdateCommandHandler
{
    public function __construct(
        private RecipeRepositoryInterface $repository
    ){}

    /**
     * @throws RecipeNotFoundException
     * @throws RecipeInvalidServingsException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(RecipeUpdateCommand $command): void
    {
        if ($recipe = $this->repository->findOne($command->id))
        {
            $recipe->rename($command->name);
            $recipe->setDescription($command->description);
            $recipe->setSource($command->source);
            $recipe->setServings($command->servings);
            $recipe->setRating($command->rating);
            $recipe->setSteps($command->steps);
            $recipe->setIngredients($command->ingredients);
            $this->repository->save($recipe);
        }
        else
        {
            throw new RecipeNotFoundException($command->id);
        }
    }
}
