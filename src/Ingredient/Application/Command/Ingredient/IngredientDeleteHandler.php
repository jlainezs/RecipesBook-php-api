<?php
namespace App\Ingredient\Application\Command\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Infrastructure\Repository\IngredientRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientDeleteHandler
{
    public function __construct(private readonly IngredientRepository $ingredientRepository)
    {}

    /**
     * @throws IngredientNotFoundException
     */
    public function __invoke(IngredientDeleteCommand $command): void
    {
        $ingredient = $this->ingredientRepository->find($command->id);

        if ($ingredient)
        {
            $this->ingredientRepository->delete($ingredient);
        } else {
            throw new IngredientNotFoundException($command->id);
        }
    }
}
