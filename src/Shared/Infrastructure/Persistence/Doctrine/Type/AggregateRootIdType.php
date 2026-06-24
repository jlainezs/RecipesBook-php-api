<?php
namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

final class AggregateRootIdType extends GuidType
{
    public const NAME = 'aggregate_root_id';

    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AggregateRootId
    {
        if ($value === null) {
            return null;
        }

        return new AggregateRootId((string) $value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof AggregateRootId) {
            return $value->toString();
        }

        return (string) $value;
    }
}
