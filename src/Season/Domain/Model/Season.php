<?php

namespace App\Season\Domain\Model;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;

final class Season extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private string                   $name,
        private DateTimeImmutable        $createdAt,
        private DateTimeImmutable        $updatedAt
    ){}

    public static function create(string $name): self
    {
        if (empty(trim($name))) {
            throw new SeasonEmptyNameException();
        }

        return new self(
            AggregateRootId::generateId(),
            $name,
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
    }
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function rename(string $name): void
    {
        if (empty(trim($name))) {
            throw new SeasonEmptyNameException();
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
