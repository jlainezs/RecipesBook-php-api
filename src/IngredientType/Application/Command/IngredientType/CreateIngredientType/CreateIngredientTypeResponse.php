<?php
namespace App\IngredientType\Application\Command\IngredientType\CreateIngredientType;

final readonly class CreateIngredientTypeResponse
{
    public function __construct(
        public string $id
    ){}
}
