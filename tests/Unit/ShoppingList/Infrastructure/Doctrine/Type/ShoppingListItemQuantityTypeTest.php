<?php

namespace App\Tests\Unit\ShoppingList\Infrastructure\Doctrine\Type;

use App\ShoppingList\Domain\Exceptions\InvalidShoppingListItemQuantity;
use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use App\ShoppingList\Infrastructure\Doctrine\Type\ShoppingListItemQuantityType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListItemQuantityTypeTest extends TestCase
{
    private ShoppingListItemQuantityType $type;
    private AbstractPlatform $platform;

    public function setUp(): void
    {
        $this->type = new ShoppingListItemQuantityType();
        $this->platform = $this->createStub(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertEquals('shopping_list_item_quantity', ShoppingListItemQuantityType::NAME);
        $this->assertEquals(ShoppingListItemQuantityType::NAME, $this->type->getName());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_db_null_to_php_null(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('validQuantities')]
    public function it_converts_a_float_to_shopping_list_item_quantity(float $value): void
    {
        $quantity = $this->type->convertToPHPValue($value, $this->platform);
        //$this->assertInstanceOf(ShoppingListItemQuantity::class, $quantity);
        $this->assertSame($value, $quantity->value());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('negativeQuantities')]
    public function it_throws_on_negative_or_zero_quantities(float $value): void
    {
        $this->expectException(InvalidShoppingListItemQuantity::class);
        $this->type->convertToPHPValue($value, $this->platform);
    }
    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_to_database_null(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('validQuantities')]
    public function it_convert_to_database_value(float $value): void
    {
        $this->assertSame(
            $value,
            $this->type->convertToDatabaseValue(new ShoppingListItemQuantity($value), $this->platform)
        );
    }

    public static function validQuantities():iterable
    {
        yield '0' => [0];
        yield '1' => [1];
        yield '1.1' => [1.1];
        yield '.99' => [.99];
    }

    public static function negativeQuantities():iterable
    {
        yield 'negative' => [-1];
        yield 'negative decimal' => [-1.1];
    }
}
