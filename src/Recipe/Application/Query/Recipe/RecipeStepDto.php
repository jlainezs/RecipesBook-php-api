<?php
namespace App\Recipe\Application\Query\Recipe;

use App\Recipe\Domain\Model\RecipeStep;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RecipeStepDto
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $id,
        public string $description,
        public int $ordering,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ){}

    public static function fromDomain(RecipeStep $recipeStep): self
    {
        return new self(
            $recipeStep->getId()->toString(),
            $recipeStep->getDescription(),
            $recipeStep->getOrdering()->value(),
            $recipeStep->getCreatedAt(),
            $recipeStep->getUpdatedAt(),
        );
    }
}
