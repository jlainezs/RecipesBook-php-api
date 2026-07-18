<?php
namespace App\Ingredient\Infrastructure\Doctrine\Type;

use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class IngredientTypeReferenceType extends GuidType
{
    public const string NAME = 'ingredient_type_reference';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?IngredientTypeReference
    {
        if ($value === null)
        {
            return null;
        }

        return new IngredientTypeReference($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null)
        {
            return null;
        }

        if ($value instanceof IngredientTypeReference)
        {
            return $value->value()->toString();
        }

        return null;
    }
}
