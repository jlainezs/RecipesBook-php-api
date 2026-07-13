<?php
namespace App\Recipe\Domain\Model;

use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
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
        private iterable $ingredients,
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
        iterable $ingredients
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
            $steps,
            $ingredients,
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

        foreach ($ingredients as $ingredientData) {
            $recipe->ingredients[] = RecipeIngredient::create(
                recipe: $recipe,
                ingredient: $ingredientData['ingredient'],
                unitOfMeasure: $ingredientData['unitOfMeasure'],
                quantity: $ingredientData['quantity'] ?? 0,
                ordering: $ingredientData['ordering'] ?? 1,
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

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setSteps(iterable $steps): void
    {
        $incomingStepsById = [];
        foreach($steps as $step){
            if (!empty($step->id))
                $incomingStepsById[$step->id] = $step;
        }

        // remove or update existing steps
        foreach($this->steps as $key => $existingStep) {
            $stepIdStr = $existingStep->getId()->toString();
            if (!isset($incomingStepsById[$stepIdStr])) {
                unset($this->steps[$key]);
            } else {
                $data = $incomingStepsById[$stepIdStr];
                $existingStep->setDescription($data->description);
                $existingStep->reorder($data->ordering);
            }
        }

        // add new steps
        foreach($steps as $data){
            if (empty($data->id))
            {
                $this->steps[] = $data;
            }
        }
    }

    public function getIngredients(): iterable {
        return $this->ingredients;
    }

    public function setIngredients(iterable $ingredients): void
    {
        $incomingIngredientsById = [];
        foreach($ingredients as $ingredient){
            if (!empty($ingredient->id))
                $incomingIngredientsById[$ingredient->id] = $ingredient;
        }

        // remove or update existing ingredients
        foreach($this->ingredients as $key => $existingIngredient) {
            $ingredientIdStr = $existingIngredient->getId()->toString();
            if (!isset($incomingIngredientsById[$ingredientIdStr])) {
                unset($this->ingredients[$key]);
            } else {
                $data = $incomingIngredientsById[$ingredientIdStr];
                $existingIngredient->setDescription($data->description);
                $existingIngredient->reorder($data->ordering);
            }
        }

        // add new ingredients
        foreach($ingredients as $data){
            if (empty($data->id)) {
                $this->ingredients[] = $data;
            }
        }
    }
}
