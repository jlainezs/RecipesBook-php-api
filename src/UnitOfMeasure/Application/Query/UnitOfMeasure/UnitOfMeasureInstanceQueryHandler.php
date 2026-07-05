<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UnitOfMeasureInstanceQueryHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureNotFoundException
     */
    public function __invoke(UnitOfMeasureInstanceQuery $query): ?UnitOfMeasureInstanceResponse
    {
        if ($uom = $this->repository->find($query->id))
        {
            return new UnitOfMeasureInstanceResponse(
                new UnitOfMeasureDto(
                    id: $uom->getId()->toString(),
                    name: $uom->getName(),
                    symbol: $uom->getSymbol(),
                    uomType: $uom->getUomType()->value,
                    createdAt: $uom->getCreatedAt(),
                    updatedAt: $uom->getUpdatedAt(),
                )
            );
        }

        throw new UnitOfMeasureNotFoundException($query->id);
    }
}
