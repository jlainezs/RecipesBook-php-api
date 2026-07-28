<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\RequiredName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

final class RequiredNameType extends StringType
{
    public const string NAME = 'required_name';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws EmptyRequiredNameException
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): RequiredName
    {
        return match (true)
        {
            $value === null => throw new EmptyRequiredNameException(),
            is_string($value) => new RequiredName($value),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return match(true) {
            $value === null => null,
            $value instanceof RequiredName => $value->value(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }
}
