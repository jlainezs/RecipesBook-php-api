<?php
namespace App\Security\Infrastructure;

use App\Security\Application\Service\UserItemsPager;
use App\Security\Domain\Repository\UserRepositoryInterface;

final readonly class DoctrineUsersListPager implements UserItemsPager
{
    public function __construct(
        private UserRepositoryInterface $repository
    ){}

    public function items(int $offset = 0, int $limit = 20): array
    {
        return $this->repository->findAll($limit, $offset);
    }
}
