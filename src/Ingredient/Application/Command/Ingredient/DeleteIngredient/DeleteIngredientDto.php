<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

use Symfony\Component\Validator\Constraints as Assert;
readonly class DeleteIngredientDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
