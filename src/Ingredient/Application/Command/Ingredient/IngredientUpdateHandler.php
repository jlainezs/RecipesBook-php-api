<?php
namespace App\Ingredient\Application\Command\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Infrastructure\Repository\IngredientRepository;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Infrastructure\Repository\IngredientTypeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class IngredientUpdateHandler
{
    public function __construct(
        private readonly IngredientRepository $ingredientRepository,
        private readonly IngredientTypeRepository $ingredientTypeRepository
    ){}

    /**
     * @throws IngredientTypeNotFoundException
     * @throws IngredientNotFoundException
     */
    public function __invoke(IngredientUpdateCommand $command): void
    {
        $ingredient = $this->ingredientRepository->findOne($command->id);

        if (!$ingredient) {
            throw new IngredientNotFoundException($command->id);
        }

        $ingredientType = $this->ingredientTypeRepository->findOne($command->ingredientTypeId);

        if ($ingredientType)
        {
            $ingredient->rename($command->name);
            $ingredient->changeDescription($command->description);
            $ingredient->changeIngredientType($ingredientType);
            $this->ingredientRepository->save($ingredient);
        }
        else
        {
            throw new IngredientTypeNotFoundException($command->ingredientTypeId);
        }
    }
}
