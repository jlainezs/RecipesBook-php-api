<?php
namespace App\IngredientType\Persistence;

use App\IngredientType\Domain\Persistence\IngredientTypeRepositoryInterface;
use App\IngredientType\Service\IngredientTypeItemsPager;

final class DoctrineIngredientTypesListPager implements IngredientTypeItemsPager
{
    public function __construct(private IngredientTypeRepositoryInterface $repository)
    {
    }

    public function items($offset = 0, $limit = 20): array
    {
        return $this->repository->findBy([], [], $limit, $offset);
    }
}
