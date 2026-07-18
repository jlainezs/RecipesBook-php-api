<?php
namespace App\IngredientType\Domain\Model;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\EmptyRequiredNameException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use DateTimeImmutable;

final class IngredientType extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws EmptyRequiredNameException
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

    /**
     * @param string $name
     * @return void
     * @throws EmptyRequiredNameException
     */
    public function rename(string $name): void
    {
        $this->name = new RequiredName($name);
    }

    public function getName(): RequiredName
    {
        return $this->name;
    }
}
