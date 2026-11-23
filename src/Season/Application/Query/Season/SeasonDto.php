<?php
namespace App\Season\Application\Query\Season;

use App\Season\Domain\Model\Season;
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

    public static function fromDomain(Season $season): self
    {
        return new self(
            $season->getId()->toString(),
            $season->getName(),
            $season->getCreatedAt(),
            $season->getUpdatedAt()
        );
    }
}
