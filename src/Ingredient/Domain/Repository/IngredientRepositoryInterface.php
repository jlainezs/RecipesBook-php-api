<?php
namespace App\Ingredient\Domain\Repository;

use App\Ingredient\Domain\Model\Ingredient;
use App\Shared\Domain\ValueObject\AggregateRootId;

interface IngredientRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?Ingredient;
    public function findAll(?int $limit = null, ?int $offset = null): array;

    public function save(Ingredient $ingredient): void;

    public function delete(Ingredient $ingredient): void;
}
