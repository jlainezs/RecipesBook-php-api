<?php
namespace App\Season\Infrastructure;

use App\Season\Application\Service\SeasonItemsPager;
use App\Season\Domain\Repository\SeasonRepositoryInterface;

final readonly class DoctrineSeasonsListPager implements SeasonItemsPager
{
    public function __construct(private SeasonRepositoryInterface $repository)
    {}

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
