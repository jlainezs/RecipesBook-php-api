<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\StringType;

final class RequiredNameType extends StringType
{
    public const string NAME = 'required_name';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws EmptyRequiredNameException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): RequiredName
    {
        if ($value === null) {
            throw new EmptyRequiredNameException();
        }

        return new RequiredName((string) $value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof RequiredName) {
            return $value->value();
        }

        return (string) $value;
    }
}
