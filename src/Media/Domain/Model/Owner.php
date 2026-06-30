<?php
namespace App\Media\Domain\Model;

use App\Shared\Domain\ValueObject\AggregateRootId;

final readonly class Owner
{
    public function __construct(
        private AggregateRootId $ownerId,
        private string $ownerClass,
    )
    {}

    public function getOwnerClass(): string
    {
        return $this->ownerClass;
    }

    public function getOwnerId(): AggregateRootId
    {
        return $this->ownerId;
    }
}
