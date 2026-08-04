<?php
namespace App\Recipe\Domain\Repository;

use App\Recipe\Domain\Model\Recipe;
use App\Shared\Domain\ValueObject\AggregateRootId;

interface RecipeRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?Recipe;
    public function findAll(?int $limit = null, ?int $offset = null): array;
    public function save(Recipe $recipe): void;
    public function delete(Recipe $recipe): void;
}
