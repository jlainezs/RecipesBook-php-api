<?php
namespace App\Recipe\Application\Command\CreateRecipe;

class CreateRecipeResponse
{
    public function __construct(
        public string $id
    ){}
}
