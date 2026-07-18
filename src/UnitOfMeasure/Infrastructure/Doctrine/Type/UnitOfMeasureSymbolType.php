<?php
namespace App\UnitOfMeasure\Infrastructure\Doctrine\Type;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class UnitOfMeasureSymbolType extends StringType
{
    public const string NAME = 'unit_of_measure_symbol';
    public const int DEFAULT_LENGTH = 5;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        // Si no s'ha definit una longitud al mapping, assignem la nostra per defecte
        $column['length'] = $column['length'] ?? self::DEFAULT_LENGTH;

        // Passem l'array modificat al mètode de la plataforma
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws UnitOfMeasureSymbolLengthException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): UnitOfMeasureSymbol
    {
        if (($value === null) || empty(trim($value))) {
            throw new UnitOfMeasureEmptySymbolException();
        }

        return new UnitOfMeasureSymbol($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof UnitOfMeasureSymbol) {
            return $value->value();
        }

        return (string) $value;
    }
}
