<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use DateTimeImmutable;

final readonly class ShoppingListItemDto
{
    public function __construct(
        public string $id,
        public string $ingredientId,
        public string $unitOfMeasureId,
        public float $quantity,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    )
    {}
}
