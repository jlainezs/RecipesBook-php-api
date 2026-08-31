<?php
namespace App\Tests\Unit\ShoppingList\Domain\Model;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\IngredientReference;
use App\Shared\Domain\ValueObject\UnitOfMeasureReference;
use App\ShoppingList\Domain\Exceptions\ShoppingListItemNotFoundException;
use App\ShoppingList\Domain\Model\ShoppingList;
use App\ShoppingList\Domain\Model\ShoppingListItem;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListTest extends TestCase
{
    #[Test]
    public function it_creates_with_a_valid_name(): void
    {
        $sl = ShoppingList::create('Shopping List', []);
        $this->assertSame('Shopping List', $sl->getName()->value());
        $this->assertInstanceOf(DateTimeImmutable::class, $sl->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $sl->getUpdatedAt());
        $this->assertNull($sl->getScheduledFor());
    }

    #[Test]
    public function it_creates_with_a_valid_id(): void
    {
        $sl = ShoppingList::create('Shopping List', []);
        $this->assertInstanceOf(AggregateRootId::class, $sl->getId());
    }

    #[Test]
    public function it_generates_a_unique_id_on_each_creation(): void
    {
        $sl1 = ShoppingList::create('Shopping List', []);
        $sl2 = ShoppingList::create('Shopping List', []);
        $this->assertNotSame($sl1->getId()->toString(), $sl2->getId()->toString());
    }

    #[Test]
    public function it_throws_on_empty_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        ShoppingList::create('', []);
    }

    #[Test]
    public function it_throws_on_whitespace_only_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        ShoppingList::create('  ', []);
    }

    #[Test]
    public function it_renames_successfully(): void
    {
        $sl = ShoppingList::create('Shopping List', []);
        $sl->rename('New Name');
        $this->assertSame('New Name', $sl->getName()->value());
    }

    #[Test]
    public function it_throws_on_rename_with_whitespace(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $sl = ShoppingList::create('Shopping List', []);
        $this->expectException(EmptyRequiredNameException::class);
        $sl->rename(' ');
    }

    #[Test]
    public function it_throws_on_rename_with_empty_name(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $sl = ShoppingList::create('Shopping List', []);
        $this->expectException(EmptyRequiredNameException::class);
        $sl->rename('');
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_adds_an_item_to_the_list():void
    {
        $ingredient = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $shoppingList = ShoppingList::create('test', []);
        $unitOfMeasure = new UnitOfMeasureReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $quantity = new ShoppingListItemQuantity(10);
        $shoppingListItem = ShoppingListItem::create(
            $shoppingList,
            $ingredient,
            $unitOfMeasure,
            $quantity
        );
        $shoppingList->addItem($shoppingListItem);
        $this->assertCount(1, $shoppingList->items());
        $this->assertSame($ingredient->value()->toString(), $shoppingListItem->getIngredientReference()->value()->toString());
        $this->assertSame($quantity->value(), $shoppingListItem->getQuantity()->value());
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ShoppingListItemNotFoundException
     */
    #[Test]
    public function it_removes_an_item_from_the_list(): void
    {
        $ingredient1 = new IngredientReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $ingredient2 = new IngredientReference('68f099d2-5348-4e33-ac48-6b0b46768c9a');
        $shoppingList = ShoppingList::create('test', []);
        $unitOfMeasure = new UnitOfMeasureReference('f21832a7-26dd-49d3-9323-d9e28df2c6c8');
        $quantity = new ShoppingListItemQuantity(10);
        $shoppingListItem1 = ShoppingListItem::create(
            $shoppingList,
            $ingredient1,
            $unitOfMeasure,
            $quantity
        );
        $shoppingListItem2 = ShoppingListItem::create(
            $shoppingList,
            $ingredient2,
            $unitOfMeasure,
            $quantity
        );

        $shoppingList->addItem($shoppingListItem1);
        $shoppingList->addItem($shoppingListItem2);
        $shoppingList->removeItem($shoppingListItem1);
        $this->assertCount(1, $shoppingList->items());

        $storedItem = $shoppingList->items()[1];
        $this->assertSame(
            $ingredient2->value()->toString(),
            $storedItem->getIngredientReference()->value()->toString()
        );
    }
}
