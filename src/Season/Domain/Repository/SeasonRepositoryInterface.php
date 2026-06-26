<?php

namespace App\Season\Domain\Repository;

use App\Season\Domain\Model\Season;

interface SeasonRepositoryInterface
{
    public function findOne(string $id): ?Season;
    public function findAll(int|null $limit = null,
                            int|null $offset = null): array;
    public function save(Season $season): void;
    public function delete(Season $season): void;
}
