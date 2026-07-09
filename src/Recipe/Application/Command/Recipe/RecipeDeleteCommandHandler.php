<?php
namespace App\Recipe\Application\Command\Recipe;

use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RecipeDeleteCommandHandler
{
    function __construct(
        private RecipeRepositoryInterface $repository
    ){}

    /**
     * @throws RecipeNotFoundException
     */
    public function __invoke(RecipeDeleteCommand $command): void
    {
        if ($recipe = $this->repository->findOne($command->id))
        {
            $this->repository->delete($recipe);
        }
        else
        {
            throw new RecipeNotFoundException($command->id);
        }
    }
}
