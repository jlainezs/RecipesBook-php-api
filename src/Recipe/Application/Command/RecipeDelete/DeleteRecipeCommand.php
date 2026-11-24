<?php
namespace App\Recipe\Application\Command\RecipeDelete;

use App\Shared\Application\Security\IsPublicOperation;

#[IsPublicOperation]
final readonly class DeleteRecipeCommand
{
    public function __construct(
        public string $id
    ){}
}
