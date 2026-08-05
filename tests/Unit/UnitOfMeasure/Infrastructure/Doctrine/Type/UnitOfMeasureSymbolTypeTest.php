<?php
namespace App\Tests\Unit\UnitOfMeasure\Infrastructure\Doctrine\Type;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Infrastructure\Doctrine\Type\UnitOfMeasureSymbolType;
use App\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureSymbolTypeTest extends TestCase
{
    private UnitOfMeasureSymbolType $type;
    private AbstractPlatform $platform;

    public function setUp(): void
    {
        $this->type = new UnitOfMeasureSymbolType();
        $this->platform = $this->createStub(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertEquals('unit_of_measure_symbol', UnitOfMeasureSymbolType::NAME);
        $this->assertEquals(UnitOfMeasureSymbolType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_string(): void
    {
        $this->assertSame(ParameterType::STRING, $this->type->getBindingType());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     */
    #[Test]
    public function it_throw_if_convert_null_to_php_null(): void
    {
        $this->expectException(UnitOfMeasureEmptySymbolException::class);
        $this->type->convertToPHPValue(null, $this->platform);
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     */
    #[Test]
    #[DataProvider('some_measure_units')]
    public function it_converts_some_string_to_symbol(string $symbolValue): void
    {
        $symbol = $this->type->convertToPHPValue($symbolValue, $this->platform);
        $this->assertSame($symbolValue, $symbol->value());
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     */
    #[Test]
    #[DataProvider('some_invalid_measure_units')]
    public function it_throw_if_convert_invalid_string_to_symbol(string $symbolValue): void
    {
        $this->expectException(UnitOfMeasureEmptySymbolException::class);
        $this->type->convertToPHPValue($symbolValue, $this->platform);
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
     * @throws UnitOfMeasureSymbolLengthException
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('some_measure_units')]
    public function it_converts_to_database_value(string $symbolValue): void
    {
        $this->assertSame($symbolValue,
            $this->type->convertToDatabaseValue(new UnitOfMeasureSymbol($symbolValue), $this->platform)
        );
    }

    public static function some_measure_units(): iterable
    {
        yield 'kg' => ['Kg'];
        yield 'gr' => ['gr'];
        yield 'L' => ['L'];
    }

    public static function some_invalid_measure_units(): iterable
    {
        yield 'empty' => [''];
        yield 'space' => [' '];
        yield 'some_spaces' => ['     '];
    }

}
