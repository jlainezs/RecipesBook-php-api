<?php
namespace App\IngredientType\Domain\Model;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;

final class IngredientType extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private string $name,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws IngredientTypeEmptyNameException
     */
    public static function create(string $name): self
    {
        if (empty(trim($name))) {
            throw new IngredientTypeEmptyNameException();
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
            throw new IngredientTypeEmptyNameException();
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
