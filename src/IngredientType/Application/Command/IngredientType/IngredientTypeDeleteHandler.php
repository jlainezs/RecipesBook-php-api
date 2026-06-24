<?php

namespace App\IngredientType\Application\Command\IngredientType;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypeDeleteHandler
{
    public function __construct(private readonly IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(IngredientTypeDeleteCommand $command): void
    {
        $ingredientType = $this->repository->findOne($command->id);
        if ($ingredientType) {
            $this->repository->delete($ingredientType);
        } else {
            throw new IngredientTypeNotFoundException($command->id);
        }
    }
}
