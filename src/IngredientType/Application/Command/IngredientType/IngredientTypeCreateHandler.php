<?php
namespace App\IngredientType\Application\Command\IngredientType;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypeCreateHandler
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientTypeEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(IngredientTypeCreateCommand $command): void
    {
        $ingredientType = IngredientType::create($command->name);
        $this->repository->save($ingredientType);
    }
}
