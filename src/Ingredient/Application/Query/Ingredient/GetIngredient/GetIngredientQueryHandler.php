<?php

namespace App\Ingredient\Application\Query\Ingredient\GetIngredient;

use App\Ingredient\Application\Query\Ingredient\IngredientDto;
use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetIngredientQueryHandler
{
    public function __construct(private IngredientRepositoryInterface $repository)
    {}

    /**
     * @throws IngredientNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(GetIngredientQuery $query): GetIngredientResponse
    {
        $ingredient = $this->repository->findOne(new AggregateRootId($query->id));

        if ($ingredient)
        {
            return new GetIngredientResponse(new IngredientDto(
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
