<?php
namespace App\Recipe\Infrastructure\Doctrine\Type;

use App\Recipe\Domain\ValueObjects\RecipeServings;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
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
        return match(true) {
            $value === null => null,
            is_integer($value) => new RecipeServings($value),
            default => throw new ConversionException(
                sprintf("Could not convert database value of type '%s' to '%s'", self::class, get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        return match(true) {
            $value === null => null,
            $value instanceof RecipeServings => $value->value(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }
}
