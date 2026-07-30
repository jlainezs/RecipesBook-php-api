<?php
namespace App\Tests\Unit\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\ValueObjects\RecipeRating;
use App\Recipe\Infrastructure\Doctrine\Type\RecipeRatingType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class RecipeRatingTypeTest extends TestCase
{
    private RecipeRatingType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new RecipeRatingType();
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertEquals('recipe_rating', RecipeRatingType::NAME);
        $this->assertEquals(RecipeRatingType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_integer(): void
    {
        $this->assertSame(ParameterType::INTEGER, $this->type->getBindingType(1));
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
    #[DataProvider('validRatings')]
    public function it_converts_valid_ratings_to_recipe_ratings(int $value): void
    {
        $rating = $this->type->convertToPHPValue($value, $this->platform);
        $this->assertInstanceOf(RecipeRating::class, $rating);
        $this->assertSame($value, $rating->value());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('invalidRatings')]
    public function it_throws_conversion_exception_for_invalid_ratings(int $value): void
    {
        $this->expectException(RecipeInvalidRatingException::class);
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
    #[DataProvider('validRatings')]
    public function it_converts_valid_ratings_to_database_values(int $value): void
    {
        $this->assertSame(
            $value,
            $this->type->convertToDatabaseValue(new RecipeRating($value), $this->platform)
        );
    }


    public static function validRatings(): iterable
    {
        // it is a small finite range. Test it all.
        yield '1' => [1];
        yield '2' => [2];
        yield '3' => [3];
        yield '4' => [4];
        yield '5' => [5];
    }

    public static function invalidRatings(): iterable
    {
        yield '0' => [0];
        yield '-1' => [-1];
        yield '6' => [6];
    }
}
