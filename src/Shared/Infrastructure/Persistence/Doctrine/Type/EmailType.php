<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObjects\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

final class EmailType extends StringType
{
    public const string NAME = 'email';
    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Email
    {
        return match(true) {
            $value === null => null,
            is_string($value) => new Email($value),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of string. Could not convert it to PHP value", get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return match(true) {
            $value === null => null,
            $value instanceof Email => $value->value(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of Email. Could not convert it to database value", get_debug_type($value))
            )
        };
    }
}
