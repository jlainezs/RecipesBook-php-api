<?php
namespace App\Ingredient\Application\Query\Ingredient;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class IngredientDto
{
    public function __construct(
        #[Assert\Uuid]
        public readonly string $id,

        #[Assert\NotBlank]
        public readonly string $name,
        public readonly string $description,

        #[Assert\Uuid]
        public readonly string $ingredientTypeId,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    ){}
}
