<?php
namespace App\IngredientType\Infrastructure;

use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;

final readonly class DoctrineIngredientTypesListPager implements IngredientTypeItemsPager
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {
    }

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
