<?php
namespace App\Recipe\Domain\Model;

use App\Recipe\Domain\Exceptions\RecipeStepEmptyDescriptionException;
use App\Recipe\Domain\Exceptions\RecipeStepInvalidOrderingException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Exception\InvalidOrderingException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\Ordering;
use DateTimeImmutable;
use Doctrine\Common\Collections\Order;

final class RecipeStep extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private Recipe $recipe,
        private Ordering $ordering,
        private string $description,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public static function create(
        Recipe $recipe,
        int $ordering,
        string $description,
    ): RecipeStep
    {
        return new self(
            id: AggregateRootId::generateId(),
            recipe: $recipe,
            ordering: new Ordering($ordering),
            description: $description,
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

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     * @throws RecipeStepEmptyDescriptionException
     */
    public function setDescription(string $description): void
    {
        if (empty(trim($description)))
        {
            throw new RecipeStepEmptyDescriptionException();
        }

        $this->description = $description;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }

}
