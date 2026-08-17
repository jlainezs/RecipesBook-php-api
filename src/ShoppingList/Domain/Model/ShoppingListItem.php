<?php
namespace App\ShoppingList\Domain\Model;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\IngredientReference;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use DateTimeImmutable;

final class ShoppingListItem extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private readonly ShoppingList $shoppingList,
        private IngredientReference $ingredient,
        private ShoppingListItemQuantity $quantity,
        private readonly DateTimeImmutable $createdAt,
        private readonly DateTimeImmutable $updatedAt
    ) {}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public static function create(ShoppingList $shoppingList, IngredientReference $ingredient, ShoppingListItemQuantity $quantity): self
    {
        return new self(
            id: AggregateRootId::generateId(),
            shoppingList: $shoppingList,
            ingredient: $ingredient,
            quantity: $quantity,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeIngredientReference(IngredientReference $ingredient): void
    {
        $this->ingredient = $ingredient;
    }

    public function getIngredientReference(): IngredientReference
    {
        return $this->ingredient;
    }
    public function getQuantity(): ShoppingListItemQuantity
    {
        return $this->quantity;
    }

    public function changeQuantity(ShoppingListItemQuantity $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getShoppingList(): ShoppingList
    {
        return $this->shoppingList;
    }
}
