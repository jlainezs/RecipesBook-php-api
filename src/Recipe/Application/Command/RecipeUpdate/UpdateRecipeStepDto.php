<?php
namespace App\Recipe\Application\Command\RecipeUpdate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateRecipeStepDto
{
    public function __construct(
        public ?string $id,
        public string $description,
        public int $ordering
    ){}
}
