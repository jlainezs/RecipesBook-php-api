<?php
namespace App\IngredientType\Application\Command\IngredientType\DeleteIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class DeleteIngredientTypeCommand
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id
    ){}
}
