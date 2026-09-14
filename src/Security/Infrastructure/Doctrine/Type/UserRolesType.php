<?php
namespace App\Security\Infrastructure\Doctrine\Type;

use App\Security\Domain\Model\UserRole;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class UserRolesType extends Type
{
    public const string NAME = 'user_roles';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL(['length' => 1024]);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): array
    {
        return match(true) {
            $value === null => [],
            is_string($value) => array_values(array_filter(array_map(
                function (string $valueRole): ?UserRole{
                    $trimmed = trim($valueRole);
                    return UserRole::tryFrom($trimmed);
                }, explode(',', $value)
            ))),
            default => throw new ConversionException(
                sprintf("Can't convert '%s' into a PHP value", get_debug_type($value))
            )
        };
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return match(true) {
            $value === null => '',
            is_array($value) => implode(
                ',',
                array_unique(
                    array_map(fn(UserRole $role) => $role->value, $value)
                )
            ),
            default => throw new ConversionException(
                sprintf("Can't convert '%s' into a database value", get_debug_type($value))
            )
        };
    }
}
