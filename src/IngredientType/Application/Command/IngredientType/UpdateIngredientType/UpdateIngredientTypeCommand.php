<?php
namespace App\IngredientType\Application\Command\IngredientType\UpdateIngredientType;


final readonly class UpdateIngredientTypeCommand
{
    public function __construct(
        public string $id,
        public string $name
    ){}
}
