<?php

namespace App\Ingredient\Application\Query\Ingredient;

use App\Ingredient\Application\Service\IngredientItemsPager;
use App\Ingredient\Domain\Model\Ingredient;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientsQueryHandler
{
    public function __construct(private IngredientItemsPager $list)
    {}

    public function __invoke(IngredientsQuery $query): IngredientsQueryResponse
    {
        $itemsDto = array_map(
            fn(Ingredient $ingredient) => new IngredientDto(
                id: $ingredient->getId()->toString(),
                name: $ingredient->getName(),
                description: $ingredient->getDescription(),
                ingredientTypeId: $ingredient->getIngredientType()->getId()->toString(),
                createdAt: $ingredient->getCreatedAt(),
                updatedAt: $ingredient->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new IngredientsQueryResponse($itemsDto);
    }
}
