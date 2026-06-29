<?php
namespace App\Ingredient\Domain\Model;

use App\Ingredient\Domain\Exceptions\IngredientEmptyNameException;
use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;

final class Ingredient extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private string $name,
        private ?string $description,
        private ?IngredientType $ingredientType,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws IngredientEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public static function create(
        string          $name,
        ?string         $description = null,
        ?IngredientType $ingredientType = null,
    )
    {
        if (empty(trim($name))) {
            throw new IngredientEmptyNameException();
        }

        return new self(
            id: AggregateRootId::generateId(),
            name: $name,
            description: $description,
            ingredientType: $ingredientType,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
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
     * @throws IngredientTypeEmptyNameException
     */
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function changeDescription(string $description): void {
        $this->description = $description;
    }

    public function getIngredientType(): ?IngredientType
    {
        return $this->ingredientType;
    }

    public function changeIngredientType(IngredientType $ingredientType): void {
        $this->ingredientType = $ingredientType;
    }
}
