<?php
namespace App\IngredientType\Application\Command\IngredientType;

use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypeDeleteCommandHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(IngredientTypeDeleteCommand $command): void
    {
        if ($ingredientType = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $this->repository->delete($ingredientType);
        } else {
            throw new IngredientTypeNotFoundException($command->id);
        }
    }
}
