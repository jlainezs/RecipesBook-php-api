<?php

namespace App\Tests\Unit\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Recipe\Infrastructure\Doctrine\Type\IngredientReferenceType;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class IngredientReferenceTypeTest extends TestCase
{
    private IngredientReferenceType $type;
    private AbstractPlatform $platform;

    public function setUp(): void
    {
        $this->type = new IngredientReferenceType();
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertSame('ingredient_reference', IngredientReferenceType::NAME);
        $this->assertSame(IngredientReferenceType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_string(): void
    {
        $this->assertSame(ParameterType::STRING, $this->type->getBindingType());
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_null_to_php_null(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('validUuidsAsStrings')]
    public function it_converts_an_uuid_to_an_ingredient_reference(string $uuid): void
    {
        $ref = $this->type->convertToPHPValue($uuid, $this->platform);
        $this->assertInstanceOf(IngredientReference::class, $ref);
        $this->assertInstanceOf(AggregateRootId::class, $ref->value());
        $this->assertSame($uuid, $ref->value()->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('nonValidUuidsAsStrings')]
    public function it_throws_when_converting_non_valid_uuids(string $uuid): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->type->convertToPHPValue($uuid, $this->platform);
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
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('validUuidsAsStrings')]
    public function it_converts_to_database_value(string $value): void
    {
        $this->assertSame($value,
            $this->type->convertToDatabaseValue(new IngredientReference($value), $this->platform)
        );
    }

    public static function validUuidsAsStrings(): iterable
    {
        yield 'zero' => ['123e4567-e89b-12d3-a456-426614174000'];
        yield 'one' => ['123e4567-e89b-12d3-a456-426614174001'];
    }

    public static function nonValidUuidsAsStrings(): iterable
    {
        yield 'invalid' => ['invalid'];
    }

}
