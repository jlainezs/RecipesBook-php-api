<?php
namespace App\Recipe\Application\Command\Recipe;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class RecipeCreateDto
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        #[Assert\GreaterThanOrEqual(1)]
        public int $servings,

        #[Assert\GreaterThanOrEqual(1)]
        #[Assert\LessThanOrEqual(5)]
        public int $rating,

        public ?string $description,
        public ?string $source,

        public iterable $steps
    ) {}
}
