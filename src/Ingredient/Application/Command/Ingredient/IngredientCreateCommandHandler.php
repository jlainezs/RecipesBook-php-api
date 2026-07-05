<?php
namespace App\Ingredient\Application\Command\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientEmptyNameException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientCreateCommandHandler
{
    public function __construct(
        private IngredientRepositoryInterface $repository,
        private IngredientTypeRepositoryInterface $ingredientTypeRepository
    ){}

    /**
     * @throws IngredientEmptyNameException
     * @throws EmptyIdNotAllowedException
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(IngredientCreateCommand $command): void
    {
        $ingredientType = $this->ingredientTypeRepository->find($command->ingredientTypeId);

        if (null === $ingredientType) {
            throw new IngredientTypeNotFoundException($command->ingredientTypeId);
        }

        $ingredient = Ingredient::create($command->name, $command->description, $ingredientType);
        $this->repository->save($ingredient);
    }
}
