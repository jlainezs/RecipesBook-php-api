<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class UpdateIngredientTypeCommandHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeEmptyNameException
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(UpdateIngredientTypeCommand $command): void
    {
        if ($ingredientType = $this->repository->findOne(new AggregateRootId($command->id)))
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
