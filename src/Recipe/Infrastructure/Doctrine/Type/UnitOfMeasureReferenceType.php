<?php
namespace App\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\ValueObjects\UnitOfMeasureReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;

final class UnitOfMeasureReferenceType extends GuidType
{
    public const string NAME = 'unit_of_measure_reference';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return match(true)
        {
            $value === null => null,
            is_string($value) => new UnitOfMeasureReference($value),
            default => throw new ConversionException(
                sprintf("Got '%s'. Could not convert it to UnitOfMeasureReference", get_debug_type($value))
            ),
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return match(true)
        {
            $value === null => null,
            $value instanceof UnitOfMeasureReference => $value->value()->toString(),
            default => throw new ConversionException(
                sprintf("I don't know how to convert '%s' to database value", get_debug_type($value))
            ),
        };
    }
}
