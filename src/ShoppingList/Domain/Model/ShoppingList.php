<?php
namespace App\ShoppingList\Domain\Model;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use DateTimeImmutable;

final class ShoppingList extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private ?DateTimeImmutable $scheduledFor,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ){}

    public static function create(string $name): self
    {
        return new self(
            id: AggregateRootId::generateId(),
            name: new RequiredName($name),
            scheduledFor: null,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getName(): RequiredName
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = new RequiredName($name);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getScheduledFor(): ?DateTimeImmutable
    {
        return $this->scheduledFor;
    }

    public function scheduleFor(DateTimeImmutable $scheduledFor): void
    {
        $this->scheduledFor = $scheduledFor;
    }
}
