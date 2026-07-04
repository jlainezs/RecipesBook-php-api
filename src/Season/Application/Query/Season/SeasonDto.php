<?php
namespace App\Season\Application\Query\Season;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class SeasonDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    )
    {}
}
