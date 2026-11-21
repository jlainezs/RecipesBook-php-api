<?php
namespace App\Ingredient\Application\Query\Ingredient;

use App\Ingredient\Domain\Model\Ingredient;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientDto
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id,

        #[Assert\NotBlank]
        public  string $name,
        public  string $description,

        #[Assert\Uuid]
        public  string $ingredientTypeId,
        public  DateTimeImmutable $createdAt,
        public  DateTimeImmutable $updatedAt
    ){}

    public static function fromDomain(Ingredient $ingredient): self
    {
        return new self(
            $ingredient->getId()->toString(),
            $ingredient->getName(),
            $ingredient->getDescription() ?? '',
            $ingredient->getIngredientType()?->value()->toString() ?? '',
            $ingredient->getCreatedAt(),
            $ingredient->getUpdatedAt()
        );
    }
}
