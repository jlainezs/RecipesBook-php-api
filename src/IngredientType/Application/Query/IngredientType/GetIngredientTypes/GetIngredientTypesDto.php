<?php
namespace App\IngredientType\Application\Query\IngredientType\GetIngredientTypes;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetIngredientTypesDto
{
    public function __construct(
        #[Assert\PositiveOrZero]
        public int $offset = 0,
        #[Assert\Positive]
        public int $limit = 20
    ){}
}
