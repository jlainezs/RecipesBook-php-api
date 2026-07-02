<?php
namespace App\Media\Infrastructure\Repository;

use App\Media\Domain\Model\Media;
use App\Media\Domain\Repository\MediaRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MediaRepository extends ServiceEntityRepository implements MediaRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function findOne(string $id): ?Media
    {
        return parent::find($id);
    }

    public function findAll(?int $limit = null, ?int $offset = null): array
    {
        return parent::findBy([], null, $limit, $offset);
    }

    public function save(Media $media): void
    {
        $this->getEntityManager()->persist($media);
        $this->getEntityManager()->flush();
    }

    public function delete(Media $media): void
    {
        $this->getEntityManager()->remove($media);
        $this->getEntityManager()->flush();
    }
}
