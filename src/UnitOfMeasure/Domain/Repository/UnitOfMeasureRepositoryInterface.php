<?php

namespace App\UnitOfMeasure\Domain\Repository;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;

interface UnitOfMeasureRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?UnitOfMeasure;
    public function findAll(?int $limit = null, ?int $offset = null): array;
    public function save(UnitOfMeasure $unitOfMeasure): void;
    public function delete(UnitOfMeasure $unitOfMeasure): void;
}
