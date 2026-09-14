<?php
namespace App\Tests\Unit\Security\Infrastructure\Doctrine\Type;

use App\Security\Domain\Model\UserRole;
use App\Security\Infrastructure\Doctrine\Type\UserRolesType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserRolesTypeTest extends TestCase
{
    private UserRolesType $type;
    private AbstractPlatform $platform;

    public function setUp(): void
    {
        $this->type = new UserRolesType();
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertEquals('user_roles', UserRolesType::NAME);
        $this->assertEquals(UserRolesType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_string(): void
    {
        $this->assertSame(ParameterType::STRING, $this->type->getBindingType());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_null_to_php_empty_array(): void
    {
        $this->assertEmpty($this->type->convertToPHPValue(null, $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_db_empty_string_to_php_empty_array(): void
    {
        $this->assertEmpty($this->type->convertToPHPValue('', $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_null_to_database_empty_string(): void
    {
        $this->assertEquals('', $this->type->convertToDatabaseValue(null, $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_empty_array_to_database_empty_string(): void
    {
        $this->assertEquals('', $this->type->convertToDatabaseValue([], $this->platform));
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_to_database_value(): void
    {
        $roles = [UserRole::USER, UserRole::ADMIN];
        $converted = explode(',', $this->type->convertToDatabaseValue($roles, $this->platform));
        $this->assertContains(UserRole::USER->value, $converted);
        $this->assertContains(UserRole::ADMIN->value, $converted);
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    public function it_converts_to_php_value(): void
    {
        $roles = implode(',', [UserRole::USER->value, UserRole::ADMIN->value]);
        $converted = $this->type->convertToPHPValue($roles, $this->platform);
        $this->assertContains(UserRole::USER, $converted);
        $this->assertContains(UserRole::ADMIN, $converted);
    }
}
