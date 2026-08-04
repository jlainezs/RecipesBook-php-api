<?php
namespace App\Ingredient\Infrastructure\Doctrine\Type;

use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;

final class IngredientTypeReferenceType extends GuidType
{
    public const string NAME = 'ingredient_type_reference';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws ConversionException
     * @throws EmptyIdNotAllowedException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?IngredientTypeReference
    {
        return match(true)
        {
            $value === null => null,
            is_string($value) => new IngredientTypeReference($value),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. Could not convert it to IngredientTypeReference", self::class, get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return match(true) {
            $value === null => null,
            $value instanceof IngredientTypeReference => $value->value()->toString(),
            default => throw new ConversionException(
                sprintf("Got '%s' instead of '%s. aaaa Could not convert it to database value", self::class, get_debug_type($value))
            )
        };
    }
}
