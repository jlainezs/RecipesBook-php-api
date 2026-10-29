<?php
namespace App\Recipe\Application\Command\RecipeUpdate;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateRecipeRouteDto
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $id
    ){}
}
