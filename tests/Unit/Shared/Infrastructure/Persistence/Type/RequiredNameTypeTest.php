<?php
namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\RequiredName;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\RequiredNameType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RequiredNameTypeTest extends TestCase
{
    private RequiredNameType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new RequiredNameType();
        $this->platform = $this->createStub(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertSame('required_name', RequiredNameType::NAME);
        $this->assertSame(RequiredNameType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_an_string(): void
    {
        $this->assertSame(ParameterType::STRING, $this->type->getBindingType());
    }

    #[Test]
    public function it_throws_when_converting_null_to_a_null_php_value(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    #[Test]
    #[DataProvider('validStrings')]
    public function it_converts_an_string_to_a_required_name(string $string): void
    {
        $requiredName = $this->type->convertToPHPValue($string, $this->platform);
        $this->assertInstanceOf(RequiredName::class, $requiredName);
        $this->assertSame($string, $requiredName->value());
    }

    #[Test]
    #[DataProvider('nonValidStrings')]
    public function it_throws_when_converting_a_non_valid_string_to_a_required_name(string $string): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $this->type->convertToPHPValue($string, $this->platform);
    }

    #[Test]
    public function it_throws_when_converting_null_to_a_database_value(): void
    {
        $this->expectException(EmptyRequiredNameException::class);
        $this->type->convertToDatabaseValue(null, $this->platform);
    }

    #[Test]
    #[DataProvider('validStrings')]
    public function it_converts_valid_names_to_database_values(string $string):void
    {
        $this->assertSame($string,
            $this->type->convertToDatabaseValue(
                new RequiredName($string), $this->platform
            )
        );
    }

    public static function validStrings(): iterable
    {
        yield 'an string' => ['an string'];
        yield 'another string' => ['another string'];
        yield 'yet another string' => ['yet another string'];
    }

    public static function nonValidStrings(): iterable
    {
        yield 'an empty string' => [''];
        yield 'a string with only spaces' => ['   '];
    }
}
