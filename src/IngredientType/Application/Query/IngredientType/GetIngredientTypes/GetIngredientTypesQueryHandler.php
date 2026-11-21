<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

use App\IngredientType\Application\Query\IngredientType\IngredientTypeDto;
use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\IngredientType\Domain\Model\IngredientType;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetIngredientTypesQueryHandler
{
    public function __construct(private IngredientTypeItemsPager $list)
    {
    }

    public function __invoke(GetIngredientTypesQuery $query): GetIngredientTypesQueryResponse
    {
        $itemsDto = array_map(
         fn(IngredientType $t) => IngredientTypeDto::fromDomain($t),
            $this->list->items($query->offset, $query->limit)
        );
        return new GetIngredientTypesQueryResponse($itemsDto);
    }
}
