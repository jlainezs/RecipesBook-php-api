<?php
namespace App\Recipe\Application\Command\RecipeDelete;

final readonly class DeleteRecipeCommand
{
    public function __construct(
        public string $id
    ){}
}
