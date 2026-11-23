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
            fn(UnitOfMeasure $uom) => UnitOfMeasureDto::fromDomain($uom),
            $this->list->items($query->offset, $query->limit)
        );
        return new GetUnitsOfMeasureQueryResponse($itemsDto);
    }
}
