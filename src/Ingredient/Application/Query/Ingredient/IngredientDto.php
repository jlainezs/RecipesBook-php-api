<?php
namespace App\Ingredient\Application\Query\Ingredient;

use DateTimeImmutable;

readonly final class IngredientDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $ingredientTypeId,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    ){}
}
