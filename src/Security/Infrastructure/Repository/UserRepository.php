<?php
namespace App\Security\Infrastructure\Repository;

use App\Security\Domain\Model\User;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOne(AggregateRootId $id): ?User
    {
        return $this->find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return $this->findBy([], null, $limit, $offset);
    }

    public function save(User $user):void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function delete(User $user):void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
