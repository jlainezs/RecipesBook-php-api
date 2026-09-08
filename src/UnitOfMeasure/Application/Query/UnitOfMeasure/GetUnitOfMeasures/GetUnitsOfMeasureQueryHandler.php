<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasures;

use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;
use App\UnitOfMeasure\Application\Service\UnitsOfMeasureItemsPager;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUnitsOfMeasureQueryHandler
{
    public function __construct(private UnitsOfMeasureItemsPager $list)
    {}

    public function __invoke(GetUnitsOfMeasureQuery $query): GetUnitsOfMeasureQueryResponse
    {
        $itemsDto = array_map(
            fn(UnitOfMeasure $uom) => new UnitOfMeasureDto(
                id: $uom->getId()->toString(),
                name: $uom->getName(),
                symbol: $uom->getSymbol()->value(),
                uomType: $uom->getUomType()->value,
                createdAt: $uom->getCreatedAt(),
                updatedAt: $uom->getUpdatedAt(),
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new GetUnitsOfMeasureQueryResponse($itemsDto);
    }
}
