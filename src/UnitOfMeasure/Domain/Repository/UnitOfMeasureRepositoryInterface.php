<?php

namespace App\UnitOfMeasure\Domain\Repository;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;

interface UnitOfMeasureRepositoryInterface
{
    public function findOne(string $id): ?UnitOfMeasure;
    public function findAll(?int $limit = null, ?int $offset = null): array;
    public function save(UnitOfMeasure $unitOfMeasure): void;
    public function delete(UnitOfMeasure $unitOfMeasure): void;
}
