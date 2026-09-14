<?php
namespace App\Tests\Unit\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\ValueObjects\Email;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\EmailType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EmailTypeTest extends TestCase
{
    private EmailType $type;
    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new EmailType();
        $this->platform = $this->createMock(AbstractPlatform::class);
    }

    #[Test]
    public function it_exposes_the_name(): void
    {
        $this->assertSame('email', EmailType::NAME);
        $this->assertSame(EmailType::NAME, $this->type->getName());
    }

    #[Test]
    public function it_binds_as_an_string(): void
    {
        $this->assertSame(ParameterType::STRING, $this->type->getBindingType());
    }

    /**
     * @throws ConversionException
     */
    #[Test]
    #[DataProvider('validEmails')]
    public function it_converts_valid_strings_to_emails(string $email): void
    {
        $this->assertSame($email,
            $this->type->convertToDatabaseValue(
                new Email($email), $this->platform
            )
        );
    }

    public static function validEmails(): iterable
    {
        yield 'an email' => ['some@email.com'];
    }

    public static function nonValidStrings(): iterable
    {
        yield 'an empty string' => [''];
        yield 'a string with only spaces' => ['   '];
    }
}
