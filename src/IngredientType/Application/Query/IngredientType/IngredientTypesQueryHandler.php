<?php
namespace App\IngredientType\Application\Query\IngredientType;

use App\IngredientType\Service\IngredientTypeItemsPager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IngredientTypesQueryHandler
{
    public function __construct(private IngredientTypeItemsPager $list)
    {
    }

    public function __invoke(IngredientTypesQuery $query): IngredientTypesQueryResponse
    {
        return new IngredientTypesQueryResponse($this->list->items($query->offset, $query->limit));
    }
}
