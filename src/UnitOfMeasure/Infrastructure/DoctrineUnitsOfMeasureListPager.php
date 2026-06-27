<?php

namespace App\UnitOfMeasure\Infrastructure;

use App\UnitOfMeasure\Application\Service\UnitsOfMeasureItemsPager;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;

final readonly class DoctrineUnitsOfMeasureListPager implements UnitsOfMeasureItemsPager
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
