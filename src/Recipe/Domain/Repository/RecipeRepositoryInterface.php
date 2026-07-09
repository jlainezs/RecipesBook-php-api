<?php
namespace App\Recipe\Domain\Repository;

use App\Recipe\Domain\Model\Recipe;

interface RecipeRepositoryInterface
{
    public function findOne(string $id): ?Recipe;
    public function findAll(?int $limit = null, ?int $offset = null): array;
    public function save(Recipe $recipe): void;
    public function delete(Recipe $recipe): void;
}
