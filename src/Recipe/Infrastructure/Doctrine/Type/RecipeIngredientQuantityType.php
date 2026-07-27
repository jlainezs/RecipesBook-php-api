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
        if ($value === null)
        {
            return null;
        }

        return new RecipeIngredientQuantity((float) $value);
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?float
    {
        if ($value === null)
        {
            return null;
        }

        if ($value instanceof RecipeIngredientQuantity)
        {
            return $value->value();
        }

        throw new ConversionException("Invalid recipe ingredient quantity type");
    }
}
