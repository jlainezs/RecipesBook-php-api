<?php
namespace App\IngredientType\Application\Query\IngredientType;

use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\IngredientType\Domain\Model\IngredientType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypesQueryHandler
{
    public function __construct(private IngredientTypeItemsPager $list)
    {
    }

    public function __invoke(IngredientTypesQuery $query): IngredientTypesQueryResponse
    {
        $itemsDto = array_map(
         fn(IngredientType $t) => new IngredientTypeDto(
                $t->getId()->toString(),
                $t->getName(),
                $t->getCreatedAt(),
                $t->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new IngredientTypesQueryResponse($itemsDto);
    }
}
