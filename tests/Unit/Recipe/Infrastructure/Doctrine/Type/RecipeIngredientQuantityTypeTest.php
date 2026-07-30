<?php
namespace App\Tests\Unit\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\Exceptions\RecipeIngredientInvalidQuantityException;
use App\Recipe\Domain\ValueObjects\RecipeIngredientQuantity;
use App\Recipe\Infrastructure\Doctrine\Type\RecipeIngredientQuantityType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeIngredientQuantityTypeTest extends TestCase
{
    private RecipeIngredientQuantityType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new RecipeIngredientQuantityType();
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    public function it_exposes_the_name(): void
    {
        $this->assertEquals('recipe_ingredient_quantity', RecipeIngredientQuantityType::NAME);
        $this->assertEquals(RecipeIngredientQuantityType::NAME, $this->type->getName());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_null_to_php_null(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('validQuantities')]
    public function it_converts_a_float_to_recipe_ingredient_quantity(float $value): void
    {
        $riq = $this->type->convertToPHPValue($value, $this->platform);
        $this->assertInstanceOf(RecipeIngredientQuantity::class, $riq);
        $this->assertSame($value, $riq->value());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('negativeQuantities')]
    public function it_throws_on_negative_or_zero_quantities(float $value): void
    {
        $this->expectException(RecipeIngredientInvalidQuantityException::class);
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
            $this->type->convertToDatabaseValue(new RecipeIngredientQuantity($value), $this->platform)
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
