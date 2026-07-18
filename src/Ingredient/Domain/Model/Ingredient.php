<?php
namespace App\Ingredient\Domain\Model;

use App\Ingredient\Domain\Exceptions\IngredientEmptyNameException;
use App\Ingredient\Domain\ValueObjects\IngredientTypeReference;
use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use DateTimeImmutable;

final class Ingredient extends AggregateRoot
{
    /**
     * @throws IngredientEmptyNameException
     */
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private ?string $description,
        private ?IngredientTypeReference $ingredientTypeReference,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws IngredientEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public static function create(
        string          $name,
        ?string         $description = null,
        ?IngredientTypeReference $ingredientType = null,
    ): Ingredient
    {
        return new self(
            id: AggregateRootId::generateId(),
            name: new RequiredName($name),
            description: $description,
            ingredientTypeReference: $ingredientType,
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
        $this->name = new RequiredName($name);
    }

    public function getName(): string
    {
        return $this->name->value();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function changeDescription(string $description): void {
        $this->description = $description;
    }

    public function getIngredientType(): ?IngredientTypeReference
    {
        return $this->ingredientTypeReference;
    }

    public function changeIngredientType(IngredientTypeReference $ingredientType): void {
        $this->ingredientTypeReference = $ingredientType;
    }
}
