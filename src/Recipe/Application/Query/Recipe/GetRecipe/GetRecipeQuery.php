<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipe;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetRecipeQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ) {}
}
