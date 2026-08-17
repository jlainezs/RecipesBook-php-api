<?php

namespace App\Tests\Unit\ShoppingList\Domain\Model;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\IngredientReference;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Model\ShoppingListItem;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListItemTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_can_be_created(): void
    {
        $ingredient = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $shoppingList = ShoppingList::create('test', []);
        $quantity = new ShoppingListItemQuantity(10);
        $shoppingListItem = ShoppingListItem::create(
            $shoppingList,
            $ingredient,
            $quantity
        );
        $this->assertSame($ingredient->value()->toString(), $shoppingListItem->getIngredientReference()->value()->toString());
        $this->assertSame($shoppingList->getId()->toString(), $shoppingListItem->getShoppingList()->getId()->toString());
        $this->assertSame($quantity->value(), $shoppingListItem->getQuantity()->value());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $ingredient = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $shoppingList = ShoppingList::create('test', []);
        $quantity = new ShoppingListItemQuantity(10);
        $shoppingListItem1 = ShoppingListItem::create(
            $shoppingList,
            $ingredient,
            $quantity
        );
        $shoppingListItem2 = ShoppingListItem::create(
            $shoppingList,
            $ingredient,
            $quantity
        );
        $this->assertNotSame($shoppingListItem1->getId()->toString(), $shoppingListItem2->getId()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_changes_ingredient(): void
    {
        $ingredient = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $shoppingList = ShoppingList::create('test', []);
        $quantity = new ShoppingListItemQuantity(10);
        $shoppingListItem = ShoppingListItem::create(
            $shoppingList,
            $ingredient,
            $quantity
        );
        $newIngredient = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c9');
        $shoppingListItem->changeIngredientReference($newIngredient);
        $this->assertSame($newIngredient->value()->toString(), $shoppingListItem->getIngredientReference()->value()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_changes_quantity(): void
    {
        $ingredient = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $shoppingList = ShoppingList::create('test', []);
        $quantity = new ShoppingListItemQuantity(10);
        $shoppingListItem = ShoppingListItem::create(
            $shoppingList,
            $ingredient,
            $quantity
        );
        $newQuantity = new ShoppingListItemQuantity(20);
        $shoppingListItem->changeQuantity($newQuantity);
        $this->assertSame($newQuantity->value(), $shoppingListItem->getQuantity()->value());
    }
}
