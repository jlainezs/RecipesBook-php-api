<?php

namespace App\IngredientType\Application\Query\IngredientType;
use DateTimeImmutable;

readonly final class IngredientTypeDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    )
    {}
}
