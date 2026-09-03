<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteIngredientCommandHandler
{
    public function __construct(private readonly IngredientRepositoryInterface $ingredientRepository)
    {}

    /**
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(DeleteIngredientCommand $command): void
    {
        $ingredient = $this->ingredientRepository->findOne(new AggregateRootId($command->id));

        if ($ingredient)
        {
            $this->ingredientRepository->delete($ingredient);
        }
        else
        {
            throw new IngredientNotFoundException($command->id);
        }
    }
}
