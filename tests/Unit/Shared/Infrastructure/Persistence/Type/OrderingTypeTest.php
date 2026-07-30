<?php
namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Exception\InvalidOrderingException;
use App\Shared\Domain\ValueObject\Ordering;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\OrderingType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

class OrderingTypeTest extends TestCase
{
    private OrderingType $type;

    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new OrderingType();
        $this->platform = $this->createStub(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_ordering_name(): void
    {
        $this->assertSame('ordering', OrderingType::NAME);
        $this->assertSame(OrderingType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_an_integer(): void
    {
        $this->assertSame(ParameterType::INTEGER, $this->type->getBindingType());
    }

    #[Test]
    public function it_declares_an_integer_column(): void
    {
        $column = ['name' => 'ordering'];

        $platform = $this->createMock(AbstractPlatform::class);
        $platform
            ->expects($this->once())
            ->method('getIntegerTypeDeclarationSQL')
            ->with($column)
            ->willReturn('INT');

        $this->assertSame('INT', $this->type->getSQLDeclaration($column, $platform));
    }

    #[Test]
    public function it_converts_null_to_a_null_php_value(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('validIntegers')]
    public function it_converts_an_integer_to_an_ordering(int $value): void
    {
        $ordering = $this->type->convertToPHPValue($value, $this->platform);

        $this->assertInstanceOf(Ordering::class, $ordering);
        $this->assertSame($value, $ordering->value());
    }

    #[Test]
    #[DataProvider('nonIntegerDatabaseValues')]
    public function it_throws_when_the_database_value_is_not_an_integer(mixed $value): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue($value, $this->platform);
    }

    #[Test]
    public function it_lets_the_ordering_invariant_fail_on_a_negative_database_value(): void
    {
        $this->expectException(InvalidOrderingException::class);

        $this->type->convertToPHPValue(-1, $this->platform);
    }

    #[Test]
    public function it_converts_null_to_a_null_database_value(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    #[Test]
    #[DataProvider('validIntegers')]
    public function it_converts_an_ordering_to_an_integer(int $value): void
    {
        $this->assertSame(
            $value,
            $this->type->convertToDatabaseValue(new Ordering($value), $this->platform)
        );
    }

    #[Test]
    #[DataProvider('nonOrderingPhpValues')]
    public function it_throws_when_the_php_value_is_not_an_ordering(mixed $value): void
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToDatabaseValue($value, $this->platform);
    }

    /**
     * @return iterable<string, array{int}>
     */
    public static function validIntegers(): iterable
    {
        yield 'zero' => [0];
        yield 'one' => [1];
        yield 'large' => [PHP_INT_MAX];
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function nonIntegerDatabaseValues(): iterable
    {
        yield 'numeric string' => ['1'];
        yield 'string' => ['ordering'];
        yield 'float' => [1.0];
        yield 'bool' => [true];
        yield 'array' => [[1]];
        yield 'object' => [new stdClass()];
        yield 'ordering' => [new Ordering(1)];
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function nonOrderingPhpValues(): iterable
    {
        yield 'int' => [1];
        yield 'numeric string' => ['1'];
        yield 'string' => ['ordering'];
        yield 'float' => [1.0];
        yield 'bool' => [true];
        yield 'array' => [[1]];
        yield 'object' => [new stdClass()];
    }
}
