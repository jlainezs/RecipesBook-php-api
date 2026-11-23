<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure\GetUnitOfMeasure;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Application\Query\UnitOfMeasure\UnitOfMeasureDto;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUnitOfMeasureQueryHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(GetUnitOfMeasureQuery $query): ?GetUnitOfMeasureResponse
    {
        if ($uom = $this->repository->findOne(new AggregateRootId($query->id)))
        {
            return new GetUnitOfMeasureResponse(
                UnitOfMeasureDto::fromDomain($uom)
            );
        }

        throw new UnitOfMeasureNotFoundException($query->id);
    }
}
