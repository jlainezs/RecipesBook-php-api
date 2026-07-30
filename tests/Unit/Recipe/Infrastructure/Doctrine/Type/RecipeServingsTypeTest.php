<?php
namespace App\Tests\Unit\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Recipe\Domain\ValueObjects\RecipeServings;
use App\Recipe\Infrastructure\Doctrine\Type\RecipeServingsType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeServingsTypeTest extends TestCase
{
    private RecipeServingsType $type;
    private AbstractPlatform $platform;

    public function setUp(): void
    {
        $this->type = new RecipeServingsType();
        $this->platform = $this->createStub(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertSame('recipe_servings', RecipeServingsType::NAME);
        $this->assertSame(RecipeServingsType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_integer(): void
    {
        $this->assertSame(ParameterType::INTEGER, $this->type->getBindingType());
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
    #[DataProvider('validServings')]
    public function it_converts_integers_to_recipe_servings(int $value): void
    {
        $servings = $this->type->convertToPHPValue($value, $this->platform);
        $this->assertInstanceOf(RecipeServings::class, $servings);
        $this->assertSame($value, $servings->value());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('invalidServings')]
    public function it_throws_exception_for_invalid_servings(int $value): void
    {
        $this->expectException(RecipeInvalidServingsException::class);
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
    #[DataProvider('validServings')]
    public function it_converts_integers_to_database_servings(int $value): void
    {
        $this->assertSame(
            $value,
            $this->type->convertToDatabaseValue(new RecipeServings($value), $this->platform));
    }

    public static function validServings(): iterable
    {
        yield '1' => [1];
        yield '2' => [2];
        yield '3' => [3];
        yield '4' => [4];
    }

    public static function invalidServings(): iterable
    {
        yield '0' => [0];
        yield '-1' => [-1];
    }
}
