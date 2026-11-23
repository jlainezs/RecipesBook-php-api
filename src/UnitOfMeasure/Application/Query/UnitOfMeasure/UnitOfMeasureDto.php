<?php
namespace App\UnitOfMeasure\Application\Query\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
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

    public static function fromDomain(UnitOfMeasure $uom): self
    {
        return new self(
            $uom->getId()->toString(),
            $uom->getName(),
            $uom->getSymbol()->value(),
            $uom->getUomType()->value,
            $uom->getCreatedAt(),
            $uom->getUpdatedAt()
        );
    }
}
