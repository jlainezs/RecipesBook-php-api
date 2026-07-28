<?php

namespace App\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\ValueObjects\RecipeIngredientQuantity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class RecipeIngredientQuantityType extends Type
{
    public const string NAME = 'recipe_ingredient_quantity';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getSmallFloatDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?RecipeIngredientQuantity
    {
        return match(true) {
            $value === null => null,
            is_numeric($value) => new RecipeIngredientQuantity((float) $value),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?float
    {
        return match (true)
        {
            $value === null => null,
            $value instanceof RecipeIngredientQuantity => $value->value(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }
}
