<?php
namespace App\IngredientType\Application\Command\IngredientType;

use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypeDeleteHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(IngredientTypeDeleteCommand $command): void
    {
        if ($ingredientType = $this->repository->findOne($command->id))
        {
            $this->repository->delete($ingredientType);
        } else {
            throw new IngredientTypeNotFoundException($command->id);
        }
    }
}
