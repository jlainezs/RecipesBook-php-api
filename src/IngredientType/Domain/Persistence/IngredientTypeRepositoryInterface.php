<?php
namespace App\IngredientType\Domain\Persistence;
use App\IngredientType\Domain\Model\IngredientType;
use Doctrine\DBAL\LockMode;

interface IngredientTypeRepositoryInterface
{
    public function find(mixed $id,  LockMode|int|null $lockMode = null, int|null $lockVersion = null): ?IngredientType;
    public function save(IngredientType $ingredientType): void;
}
