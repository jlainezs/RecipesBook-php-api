<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;


readonly final class UnitOfMeasureDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,

        #[Assert\NotBlank]
        public string $symbol,

        #[Assert\GreaterThanOrEqual(0)]
        #[Assert\LessThanOrEqual(2)]
        public int $uomType,

        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ){}
}
