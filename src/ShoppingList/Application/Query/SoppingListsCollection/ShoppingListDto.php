<?php
namespace App\ShoppingList\Application\Query\SoppingListsCollection;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class ShoppingListDto
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,
        public ?DateTimeImmutable $scheduledFor,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ){}
}
