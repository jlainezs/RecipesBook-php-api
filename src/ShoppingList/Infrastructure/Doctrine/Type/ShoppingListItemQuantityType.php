<?php
namespace App\ShoppingList\Infrastructure\Doctrine\Type;

use App\ShoppingList\Domain\ValueObjects\ShoppingListItemQuantity;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class ShoppingListItemQuantityType extends Type
{
    public const string NAME = 'shopping_list_item_quantity';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getSmallFloatDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ShoppingListItemQuantity
    {
        return match(true){
            $value === null => null,
            is_numeric($value) => new ShoppingListItemQuantity((float)$value),
            default => throw new ConversionException(
                sprintf("Can't convert '%s' into a PHP value", get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?float
    {
        return match(true){
            $value === null => null,
            $value instanceof ShoppingListItemQuantity => $value->value(),
            default => throw new ConversionException(
                sprintf("Can't convert '%s' into a database value", get_debug_type($value))
            )
        };
    }
}
