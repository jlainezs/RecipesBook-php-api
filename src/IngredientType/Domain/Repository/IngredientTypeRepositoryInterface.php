<?php
namespace App\IngredientType\Domain\Repository;

use App\IngredientType\Domain\Model\IngredientType;
use App\Shared\Domain\ValueObject\AggregateRootId;

interface IngredientTypeRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?IngredientType;
    public function findAll(int|null $limit = null,
                           int|null $offset = null): array;

    public function save(IngredientType $ingredientType): void;

    public function delete(IngredientType $ingredientType): void;
}
