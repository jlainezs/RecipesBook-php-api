<?php
namespace App\ShoppingList\Application\Query;

use App\ShoppingList\Domain\Model\ShoppingList;
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

    public static function fromDomain(ShoppingList $shoppingList): self
    {
        $items = [];
        foreach ($shoppingList->items() as $item)
        {
            $items[] = ShoppingListItemDto::fromDomain($item);
        }

        return new self(
            $shoppingList->getId()->toString(),
            $shoppingList->getName()->value(),
            $items,
            $shoppingList->getScheduledFor(),
            $shoppingList->getCreatedAt(),
            $shoppingList->getUpdatedAt()
        );
    }
}
