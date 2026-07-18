<?php

namespace App\Season\Domain\Model;

use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use DateTimeImmutable;

final class Season extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyRequiredNameException
     * @throws EmptyIdNotAllowedException
     */
    public static function create(string $name): self
    {
        return new self(
            AggregateRootId::generateId(),
            new RequiredName($name),
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
        $this->name = new RequiredName($name);
    }

    public function getName(): string
    {
        return $this->name->value();
    }
}
