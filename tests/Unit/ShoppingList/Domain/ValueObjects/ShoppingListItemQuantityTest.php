<?php

namespace App\Tests\Unit\ShoppingList\Domain\ValueObjects;

use App\ShoppingList\Domain\Exceptions\InvalidShoppingListItemQuantity;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListItemQuantityTest extends TestCase
{
    #[Test]
    #[DataProvider('wrongQuantities')]
    public function it_should_throw_withWrongQuantities(float $quantity)
    {
        $this->expectException(InvalidShoppingListItemQuantity::class);
        new ShoppingListItemQuantity($quantity);
    }

    #[Test]
    #[DataProvider('goodQuantities')]
    public function it_should_keep_the_values(float $quantity)
    {
        $slQ = new ShoppingListItemQuantity($quantity);
        $this->assertSame($slQ->value(), $quantity);
    }

    #[Test]
    #[DataProvider('goodQuantities')]
    public function it_should_convert_to_string_properly(float $quantity)
    {
        $slQ = new ShoppingListItemQuantity($quantity);
        $this->assertSame((string) $quantity, (string) $slQ->value());
    }

    #[Test]
    #[DataProvider('goodQuantities')]
    public function it_should_compare_equal(float $quantity)
    {
        $quantity1 = new ShoppingListItemQuantity($quantity);
        $quantity2 = new ShoppingListItemQuantity($quantity);
        $this->assertTrue($quantity1->equals($quantity2));
    }

    public static function wrongQuantities(): iterable
    {
        yield 'negative' => [-1];
    }

    public static function goodQuantities(): iterable
    {
        yield 'integer' => [1];
        yield 'zero' => [0];
        yield 'float' => [1.3];
    }
}
