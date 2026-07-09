<?php
namespace App\Recipe\Application\Command\Recipe;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Infrastructure\Repository\RecipeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeUpdateCommandHandler
{
    public function __construct(
        private RecipeRepository $recipeRepository
    ){}

    /**
     * @throws RecipeNotFoundException
     * @throws RecipeInvalidServingsException
     */
    public function __invoke(RecipeUpdateCommand $command): void
    {
        if ($recipe = $this->recipeRepository->findOne($command->id))
        {
            $recipe->rename($command->name);
            $recipe->setDescription($command->description);
            $recipe->setSource($command->source);
            $recipe->setServings($command->servings);
            $recipe->setRating($command->rating);
            $this->recipeRepository->save($recipe);
        }
        else
        {
            throw new RecipeNotFoundException($command->id);
        }
    }
}
