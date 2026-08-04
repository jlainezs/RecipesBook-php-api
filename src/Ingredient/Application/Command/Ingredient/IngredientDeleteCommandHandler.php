<?php
namespace App\Ingredient\Application\Command\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Infrastructure\Repository\IngredientRepository;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientDeleteCommandHandler
{
    public function __construct(private readonly IngredientRepository $ingredientRepository)
    {}

    /**
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(IngredientDeleteCommand $command): void
    {
        $ingredient = $this->ingredientRepository->findOne(new AggregateRootId($command->id));

        if ($ingredient)
        {
            $this->ingredientRepository->delete($ingredient);
        } else {
            throw new IngredientNotFoundException($command->id);
        }
    }
}
