<?php
namespace App\Ingredient\Infrastructure;

use App\Ingredient\Application\Service\IngredientItemsPager;
use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;

final readonly class DoctrineIngredientsListPager implements IngredientItemsPager
{
    public function __construct(private IngredientRepositoryInterface $repository)
    {
    }

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
