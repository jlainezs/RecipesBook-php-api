<?php
namespace App\Recipe\Application\Command\Recipe;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RecipeStepUpdateDto
{
    public function __construct(
        #[Assert\Uuid]
        public ?string $id,
        public string $description,
        public int $ordering
    ){}
}
