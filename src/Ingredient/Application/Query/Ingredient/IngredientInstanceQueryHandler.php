<?php

namespace App\Ingredient\Application\Query\Ingredient;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Model\Ingredient;
use App\Ingredient\Infrastructure\Repository\IngredientRepository;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientInstanceQueryHandler
{
    public function __construct(private IngredientRepository $repository)
    {}

    /**
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(IngredientInstanceQuery $query): IngredientInstanceResponse
    {
        $ingredient = $this->repository->findOne(new AggregateRootId($query->id));

        if ($ingredient)
        {
            return new IngredientInstanceResponse(new IngredientDto(
                id: $ingredient->getId()->toString(),
                name: $ingredient->getName(),
                description: $ingredient->getDescription(),
                ingredientTypeId: $ingredient->getIngredientType()->value()->toString(),
                createdAt: $ingredient->getCreatedAt(),
                updatedAt: $ingredient->getUpdatedAt()
            ));
        }

        throw new IngredientNotFoundException($query->id);
    }
}
