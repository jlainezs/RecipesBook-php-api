<?php
namespace App\ShoppingList\Application\Query\ShoppingListInstance;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly final class ShoppingListDto
{
    /**
     * @param string $id
     * @param string $name
     * @param array $items
     * @param ?DateTimeImmutable $scheduledFor
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     */
    public function __construct(
        #[Assert\Uuid]
        public string $id,

        #[Assert\NotBlank]
        public string $name,
        public array $items,
        public ?DateTimeImmutable $scheduledFor,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt
    ){}
}
