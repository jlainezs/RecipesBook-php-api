<?php
namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AggregateRootIdType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class AggregateRootIdTypeTest extends TestCase
{
    private AggregateRootIdType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new AggregateRootIdType();
        $this->platform = $this->createStub(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertSame('aggregate_root_id', AggregateRootIdType::NAME);
        $this->assertSame(AggregateRootIdType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_an_string(): void
    {
        $this->assertSame(ParameterType::STRING, $this->type->getBindingType());
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_null_to_a_null_php_value(): void
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    #[Test]
    #[DataProvider('validUuidsAsStrings')]
    public function it_converts_an_uuid_to_an_aggregate_root_id(string $value): void
    {
        $id = $this->type->convertToPHPValue($value, $this->platform);
        $this->assertInstanceOf(AggregateRootId::class, $id);
        $this->assertSame($value, $id->toString());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    #[DataProvider('nonValidUuidsAsStrings')]
    public function it_throws_an_exception_when_converting_an_invalid_uuid_to_an_aggregate_root_id(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->type->convertToPHPValue($value, $this->platform);
    }

    #[Test]
    #[DataProvider('emptyUuidsAsStrings')]
    public function it_throws_an_exception_when_converting_an_empty_uuid_to_an_aggregate_root_id(string $value): void
    {
        $this->expectException(EmptyIdNotAllowedException::class);
        $this->type->convertToPHPValue($value, $this->platform);
    }

    #[Test]
    public function it_converts_null_to_a_null_database_value(): void
    {
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));
    }

    #[Test]
    #[DataProvider('validUuidsAsStrings')]
    public function it_converts_aggregate_root_id_to_database_value(string $value): void
    {
        $this->assertSame($value,
            $this->type->convertToDatabaseValue(new AggregateRootId($value), $this->platform)
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

    public static function emptyUuidsAsStrings(): iterable
    {
        yield 'empty' => [''];
    }

 }
