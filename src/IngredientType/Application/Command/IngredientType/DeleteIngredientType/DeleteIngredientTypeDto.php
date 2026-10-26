<?php
namespace App\IngredientType\Application\Command\IngredientType\DeleteIngredientType;

use Symfony\Component\Validator\Constraints as Assert;
class DeleteIngredientTypeDto
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Uuid]
        public string $id
    ){}
}
