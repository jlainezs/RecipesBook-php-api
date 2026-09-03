<?php
namespace App\Ingredient\Application\Command\Ingredient\DeleteIngredient;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class DeleteIngredientCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ){}
}
