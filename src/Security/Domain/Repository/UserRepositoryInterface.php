<?php
namespace App\Security\Domain\Repository;

use App\Security\Domain\Model\User;
use App\Shared\Domain\ValueObjects\AggregateRootId;

interface UserRepositoryInterface
{
    public function findOne(AggregateRootId $id): ?User;
    public function findAll(?int $limit = null, ?int $offset = null): array;
    public function save(User $user): void;
    public function delete(User $user): void;
}
