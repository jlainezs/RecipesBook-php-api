<?php
namespace App\Recipe\Domain\Model;

use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\HasTimeStampInterface;
use App\Shared\Domain\ValueObject\AggregateRootId;
use DateTimeImmutable;
use Traversable;

final class Recipe extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private string $name,
        private int $servings,
        private int $rating,
        private ?string $description,
        private ?string $source,
        private iterable $steps,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws RecipeEmptyNameException
     * @throws EmptyIdNotAllowedException
     * @throws RecipeInvalidServingsException
     */
    public static function create(
        string $name,
        int $servings,
        int $rating,
        ?string $description = null,
        ?string $source = null,
        iterable $steps,
    ) : self
    {
        if (empty(trim($name))) {
            throw new RecipeEmptyNameException();
        }

        if ($rating < 0 || $rating > 5) {
            throw new RecipeInvalidRatingException($rating);
        }

        if ($servings <= 0) {
            throw new RecipeInvalidServingsException($servings);
        }

        $recipe = new self(
            AggregateRootId::generateId(),
            $name,
            $servings,
            $rating,
            $description,
            $source,
            [],
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );

        foreach ($steps as $step) {
            $recipe->steps[] = RecipeStep::create(
                recipe: $recipe,
                ordering: $step['ordering'] ?? 0,
                description: $step['description'] ?? ''
            );
        }

        return $recipe;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        if (empty(trim($name))) {
            throw new RecipeEmptyNameException();
        }

        $this->name = $name;
    }

    public function getServings(): int
    {
        return $this->servings;
    }

    /**
     * @throws RecipeInvalidServingsException
     */
    public function setServings(int $servings): void
    {
        if ($servings <= 0) {
            throw new RecipeInvalidServingsException($servings);
        }

        $this->servings = $servings;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @throws RecipeInvalidRatingException
     */
    public function setRating(int $rating): void
    {
        if ($rating < 0 || $rating > 5) {
            throw new RecipeInvalidRatingException($rating);
        }

        $this->rating = $rating;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getSteps(): iterable
    {
        return $this->steps;
    }

    public function setSteps(iterable $steps): void
    {
        $this->steps = $steps;
    }
}
