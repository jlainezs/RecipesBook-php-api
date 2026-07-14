<?php

namespace App\Recipe\Application\Command\RecipeUpdate;

use Symfony\Component\Validator\Constraints as Assert;

class RecipeUpdateDto
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

        public array $steps,
        public array $ingredients
    ) {}

}
