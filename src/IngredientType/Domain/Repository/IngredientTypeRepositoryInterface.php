<?php
namespace App\IngredientType\Domain\Repository;

use App\IngredientType\Domain\Model\IngredientType;

interface IngredientTypeRepositoryInterface
{
    public function findOne(mixed $id): ?IngredientType;
    public function findAll(int|null $limit = null,
                           int|null $offset = null): array;

    public function save(IngredientType $ingredientType): void;
}
