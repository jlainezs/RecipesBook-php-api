<?php
namespace App\Recipe\Application\Query\Recipe;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class RecipeStepDto
{
    public function __construct(
        #[Assert\Uuid]
        public  string $id,

        public string $description,
        public int $ordering,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ){}
}
