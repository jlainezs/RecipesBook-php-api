<?php
namespace App\Recipe\Infrastructure;

use App\Recipe\Application\Service\RecipeItemsPager;
use App\Recipe\Domain\Repository\RecipeRepositoryInterface;

final readonly class DoctrineRecipesListPager implements RecipeItemsPager
{
    public function __construct(private RecipeRepositoryInterface $repository)
    {}

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
