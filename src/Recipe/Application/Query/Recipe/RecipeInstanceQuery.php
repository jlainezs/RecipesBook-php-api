<?php
namespace App\Recipe\Application\Query\Recipe;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class RecipeInstanceQuery
{
    public function __construct(
        #[Assert\Uuid]
        public string $id
    ) {}
}
