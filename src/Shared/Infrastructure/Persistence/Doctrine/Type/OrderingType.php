<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Ordering;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class OrderingType extends Type
{
    public const string NAME = 'ordering';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function getBindingType(): ParameterType
    {
        return ParameterType::INTEGER;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Ordering
    {
        return match (true)
        {
            $value === null => null,
            is_int($value) => new Ordering($value),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        return match(true) {
            $value === null => null,
            $value instanceof Ordering => $value->value(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }
}
