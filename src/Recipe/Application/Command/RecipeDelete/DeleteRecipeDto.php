<?php
namespace App\Recipe\Application\Command\RecipeDelete;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteRecipeDto
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public readonly string $id
    ){}
}
