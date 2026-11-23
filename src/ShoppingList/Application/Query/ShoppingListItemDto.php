<?php
namespace App\ShoppingList\Application\Query;

use App\ShoppingList\Domain\Model\ShoppingListItem;
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
    ){}

    public static function fromDomain(ShoppingListItem $item): self
    {
        return new self(
            $item->getId()->toString(),
            $item->getIngredientReference()->value(),
            $item->getUnitOfMeasure()->value(),
            $item->getQuantity()->value(),
            $item->getCreatedAt(),
            $item->getUpdatedAt()
        );
    }
}
