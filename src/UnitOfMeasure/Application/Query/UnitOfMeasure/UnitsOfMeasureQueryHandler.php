<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;
use App\UnitOfMeasure\Application\Service\UnitsOfMeasureItemsPager;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UnitsOfMeasureQueryHandler
{
    public function __construct(private UnitsOfMeasureItemsPager $list)
    {}

    public function __invoke(UnitsOfMeasureQuery $query): UnitsOfMeasureQueryResponse
    {
        $itemsDto = array_map(
            fn(UnitOfMeasure $uom) => new UnitOfMeasureDto(
                id: $uom->getId()->toString(),
                name: $uom->getName(),
                symbol: $uom->getSymbol(),
                uomType: $uom->getUomType()->value,
                createdAt: $uom->getCreatedAt(),
                updatedAt: $uom->getUpdatedAt(),
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new UnitsOfMeasureQueryResponse($itemsDto);
    }
}
