<?php
namespace App\Media\Domain\Repository;

use App\Media\Domain\Model\Media;

interface MediaRepositoryInterface
{
    public function findOne(string $id): ?Media;

    public function findAll(?int $limit = null, ?int $offset = null): array;

    public function save(Media $media): void;

    public function delete(Media $media): void;
}
