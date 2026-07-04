<?php
namespace App\IngredientType\Application\Command\IngredientType;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class IngredientTypeUpdateCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name
    ){}
}
