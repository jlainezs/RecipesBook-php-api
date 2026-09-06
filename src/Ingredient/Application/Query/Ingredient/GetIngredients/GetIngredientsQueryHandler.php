<?php

namespace App\Ingredient\Application\Query\Ingredient\GetIngredients;

use App\Ingredient\Application\Query\Ingredient\IngredientDto;
use App\Ingredient\Application\Service\IngredientItemsPager;
use App\Ingredient\Domain\Model\Ingredient;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetIngredientsQueryHandler
{
    public function __construct(private IngredientItemsPager $list)
    {}

    public function __invoke(GetIngredientsQuery $query): GetIngredientsQueryResponse
    {
        $itemsDto = array_map(
            fn(Ingredient $ingredient) => new IngredientDto(
                id: $ingredient->getId()->toString(),
                name: $ingredient->getName(),
                description: $ingredient->getDescription(),
                ingredientTypeId: $ingredient->getIngredientType()->value()->toString(),
                createdAt: $ingredient->getCreatedAt(),
                updatedAt: $ingredient->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new GetIngredientsQueryResponse($itemsDto);
    }
}
