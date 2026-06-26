<?php

namespace App\Season\Application\Query\Season;
use DateTimeImmutable;

readonly final class SeasonDto
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    )
    {}
}
