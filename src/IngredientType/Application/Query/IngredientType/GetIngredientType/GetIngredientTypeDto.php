<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientType;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetIngredientTypeDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ){}
}
