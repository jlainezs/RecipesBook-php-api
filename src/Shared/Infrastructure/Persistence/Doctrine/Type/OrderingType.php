<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\ValueObject\Ordering;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class OrderingType extends Type
{
    public const string NAME = 'ordering';

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

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Ordering
    {
        if ($value === null)
        {
            return null;
        }

        return new Ordering($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        if ($value === null)
        {
            return null;
        }

        if ($value instanceof Ordering)
        {
            return $value->value();
        }

        return $value->value();
    }
}
