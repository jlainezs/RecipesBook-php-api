<?php
namespace App\Recipe\Domain\Model;

use App\Ingredient\Domain\Model\Ingredient;
use App\Recipe\Domain\ValueObjects\IngredientReference;
use App\Recipe\Domain\ValueObjects\RecipeIngredientQuantity;
use App\Recipe\Domain\ValueObjects\UnitOfMeasureReference;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\InvalidOrderingException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\Ordering;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use DateTimeImmutable;

final class RecipeIngredient extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private Recipe $recipe,
        private IngredientReference $ingredient,
        private UnitOfMeasureReference $unitOfMeasure,
        private RecipeIngredientQuantity $quantity,
        private Ordering $ordering,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public static function create(
        Recipe $recipe,
        IngredientReference $ingredient,
        UnitOfMeasureReference $unitOfMeasure,
        float $quantity,
        int $ordering
    ): RecipeIngredient
    {
        return new self(
            id: AggregateRootId::generateId(),
            recipe: $recipe,
            ingredient: $ingredient,
            unitOfMeasure: $unitOfMeasure,
            quantity: new RecipeIngredientQuantity($quantity),
            ordering: new Ordering($ordering),
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

    public function getOrdering(): Ordering
    {
        return $this->ordering;
    }

    /**
     * @param int $ordering
     * @returns void
     * @throws InvalidOrderingException
     */
    public function reorder(int $ordering): void
    {
        $this->ordering = new Ordering($ordering);
    }

    public function getQuantity(): RecipeIngredientQuantity
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = new RecipeIngredientQuantity($quantity);
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

    public function getUnitOfMeasure(): UnitOfMeasureReference
    {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(UnitOfMeasureReference $unitOfMeasure): void
    {
        $this->unitOfMeasure = $unitOfMeasure;
    }

    public function getIngredient(): IngredientReference
    {
        return $this->ingredient;
    }

    public function setIngredient(IngredientReference $ingredient): void
    {
        $this->ingredient = $ingredient;
    }
}
