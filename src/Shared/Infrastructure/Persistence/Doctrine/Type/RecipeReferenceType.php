<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\RecipeReference;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\GuidType;

final class RecipeReferenceType extends GuidType
{
    public const string NAME = 'recipe_reference';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws EmptyIdNotAllowedException
     * @throws ConversionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?RecipeReference
    {
        return match(true)
        {
            $value === null => null,
            is_string($value) => new RecipeReference(new AggregateRootId($value)),
            default => throw new ConversionException(
                sprintf("Could not convert '%s' to RecipeRerence", get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return match(true)
        {
            $value === null => null,
            $value instanceof RecipeReference => $value->value()->toString(),
            default => throw new ConversionException(
                sprintf("Could not convert '%s' to database value", get_debug_type($value))
            )
        };
    }
}
