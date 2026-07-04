<?php
namespace App\IngredientType\Application\Command\IngredientType;

use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientTypeDeleteCommand
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id
    ){}
}
