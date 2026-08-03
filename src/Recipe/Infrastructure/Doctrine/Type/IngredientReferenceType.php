<?php
namespace App\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;

final class IngredientReferenceType extends GuidType
{
    public const string NAME = 'ingredient_reference';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?IngredientReference
    {
        return match(true)
        {
            $value === null => null,
            is_string($value) => new IngredientReference($value),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to IngredientReference", self::class, get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return match(true)
        {
            $value === null => null,
            $value instanceof IngredientReference => $value->value()->toString(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }
}
