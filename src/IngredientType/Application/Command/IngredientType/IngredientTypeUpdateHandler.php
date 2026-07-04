<?php
namespace App\IngredientType\Application\Command\IngredientType;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class IngredientTypeUpdateHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeEmptyNameException
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(IngredientTypeUpdateCommand $command): void
    {
        if ($ingredientType = $this->repository->findOne($command->id))
        {
            $ingredientType->rename($command->name);
            $this->repository->save($ingredientType);
        }
        else
        {
            throw new IngredientTypeNotFoundException($command->id);
        }
    }
}
