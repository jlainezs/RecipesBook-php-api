<?php
namespace App\Recipe\Domain\Model;

use App\Ingredient\Domain\Model\Ingredient;
use App\Recipe\Domain\Exceptions\RecipeIngredientInvalidOrderingException;
use App\Recipe\Domain\Exceptions\RecipeIngredientInvalidQuantityException;
use App\Recipe\Domain\Exceptions\RecipeStepInvalidOrderingException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use DateTimeImmutable;

final class RecipeIngredient extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private Recipe $recipe,
        private Ingredient $ingredient,
        private UnitOfMeasure $unitOfMeasure,
        private float $quantity,
        private int $ordering,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public static function create(
        Recipe $recipe,
        Ingredient $ingredient,
        UnitOfMeasure $unitOfMeasure,
        float $quantity,
        int $ordering
    ): RecipeIngredient
    {
        if ($ordering <= 0) {
            throw new RecipeIngredientInvalidOrderingException();
        }

        if ($quantity <= 0) {
            throw new RecipeIngredientInvalidQuantityException();
        }

        return new self(
            id: AggregateRootId::generateId(),
            recipe: $recipe,
            ingredient: $ingredient,
            unitOfMeasure: $unitOfMeasure,
            quantity: $quantity,
            ordering: $ordering,
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

    public function getOrdering(): int
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     * @returns void
     * @throws RecipeStepInvalidOrderingException
     */
    public function reorder(int $ordering): void
    {
        if ($ordering <= 0) {
            throw new RecipeIngredientInvalidOrderingException();
        }

        $this->ordering = $ordering;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        if ($quantity <= 0) {
            throw new RecipeIngredientInvalidQuantityException();
        }

        $this->quantity = $quantity;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function getUnitOfMeasure(): UnitOfMeasure
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(UnitOfMeasure $unitOfMeasure): void
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): void
    {
        $this->ingredient = $ingredient;
    }
}
