<?php
namespace App\Recipe\Application\Query\Recipe;

use App\Recipe\Domain\Model\Recipe;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class RecipeDto
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\GreaterThanOrEqual(1)]
        public int $servings,

        #[Assert\GreaterThanOrEqual(1)]
        #[Assert\LessThanOrEqual(5)]
        public int $rating,

        public ?string $description,
        public ?string $source,

        public iterable $steps,
        public iterable $ingredients,

        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ) {}

    public static function fromDomain(Recipe $recipe): self
    {
        return new RecipeDto(
            $recipe->getId()->toString(),
            $recipe->getName(),
            $recipe->getServings()->value(),
            $recipe->getRating()->value(),
            $recipe->getDescription(),
            $recipe->getSource(),
            $recipe->getSteps(),
            $recipe->getIngredients(),
            $recipe->getCreatedAt(),
            $recipe->getUpdatedAt(),
        );
    }
}
