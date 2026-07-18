<?php
namespace App\Ingredient\Application\Command\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Ingredient\Infrastructure\Repository\IngredientRepository;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Infrastructure\Repository\IngredientTypeRepository;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class IngredientUpdateCommandHandler
{
    public function __construct(
        private IngredientRepository $ingredientRepository,
        private IngredientTypeRepository $ingredientTypeRepository
    ){}

    /**
     * @throws IngredientTypeNotFoundException
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
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
            $ingredient->changeIngredientType(new IngredientTypeReference($command->ingredientTypeId));
            $this->ingredientRepository->save($ingredient);
        }
        else
        {
            throw new IngredientTypeNotFoundException($command->ingredientTypeId);
        }
    }
}
