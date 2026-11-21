<?php
namespace App\IngredientType\Application\Query\IngredientType;

use App\IngredientType\Domain\Model\IngredientType;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientTypeDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ){}

    public static function fromDomain(IngredientType $ingredientType): self
    {
        return new self(
            $ingredientType->getId()->toString(),
            $ingredientType->getName()->value(),
            $ingredientType->getCreatedAt(),
            $ingredientType->getUpdatedAt()
        );
    }
}
