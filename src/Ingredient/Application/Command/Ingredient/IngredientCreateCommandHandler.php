<?php
namespace App\Ingredient\Application\Command\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientEmptyNameException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Application\Query\IngredientType\FindIngredientTypeReferenceQuery;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientCreateCommandHandler
{
    public function __construct(
        private IngredientRepositoryInterface $repository,
        private QueryBus $queryBus
    ){}

    /**
     * @throws IngredientTypeNotFoundException
     */
    private function findIngredientTypeReference(string $ingredientTypeId): IngredientTypeReference
    {
        $envelope = $this->queryBus->ask(
            new FindIngredientTypeReferenceQuery($ingredientTypeId)
        );

        if ($envelope === null)
        {
            throw new IngredientTypeNotFoundException($ingredientTypeId);
        }

        return $envelope;
    }

    /**
     * @throws IngredientEmptyNameException
     * @throws EmptyIdNotAllowedException
     * @throws IngredientTypeNotFoundException
     */
    public function __invoke(IngredientCreateCommand $command): void
    {
        $ingredientTypeReference = null;

        if ($command->ingredientTypeId !== null) {
            $ingredientTypeReference = $this->findIngredientTypeReference($command->ingredientTypeId);
        }

        if (null === $ingredientTypeReference) {
            throw new IngredientTypeNotFoundException($command->ingredientTypeId);
        }

        $ingredient = Ingredient::create(
            $command->name,
            $command->description,
            $ingredientTypeReference
        );
        $this->repository->save($ingredient);
    }
}
