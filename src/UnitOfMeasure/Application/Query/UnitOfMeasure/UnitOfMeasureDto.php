<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;

use DateTimeImmutable;

readonly final class UnitOfMeasureDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $symbol,
        public readonly int $uomType,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    ){}
}
