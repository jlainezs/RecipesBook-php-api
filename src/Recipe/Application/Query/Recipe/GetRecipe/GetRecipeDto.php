<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipe;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRecipeDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
