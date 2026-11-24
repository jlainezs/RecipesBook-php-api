<?php
namespace App\Recipe\Application\Query\Recipe\GetRecipe;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class GetRecipeQuery
{
    public function __construct(
        public string $id
    ) {}
}
