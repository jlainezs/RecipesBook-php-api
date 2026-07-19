<?php
namespace App\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\ValueObjects\RecipeServings;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class RecipeServingsType extends Type
{
    public const string NAME = 'recipe_servings';

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

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?RecipeServings
    {
        if ($value === null)
        {
            return null;
        }

        return new RecipeServings($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof RecipeServings)
        {
            return $value->value();
        }

        return $value?->value;
    }
}
