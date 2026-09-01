<?php
namespace App\ShoppingList\Domain\Model;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\RequiredName;
use App\ShoppingList\Domain\Exceptions\ShoppingListItemNotFoundException;
use DateTimeImmutable;

final class ShoppingList extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private iterable $items,
        private ?DateTimeImmutable $scheduledFor,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ){}

    public static function create(string $name, iterable $items): self
    {
        return new self(
            id: AggregateRootId::generateId(),
            name: new RequiredName($name),
            items: $items,
            scheduledFor: null,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getName(): RequiredName
    {
        return $this->name;
    }

    public function getItems(): iterable
    {
        return $this->items;
    }

    public function rename(string $name): void
    {
        $this->name = new RequiredName($name);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getScheduledFor(): ?DateTimeImmutable
    {
        return $this->scheduledFor;
    }

    public function scheduleFor(DateTimeImmutable $scheduledFor): void
    {
        $this->scheduledFor = $scheduledFor;
    }

    public function addItem(ShoppingListItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItem(AggregateRootId $id): ?ShoppingListItem
    {
        foreach ($this->items as $item)
        {
            if ($item->getId()->toString() === $id->toString())
            {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param ShoppingListItem[] $newItems
     * @throws ShoppingListItemNotFoundException
     */
    public function setItems(array $newItems): void
    {
        $notProcessedItems = [];
        foreach ($this->getItems() as $item)
        {
            $notProcessedItems[$item->getId()->toString()] = $item;
        }

        foreach ($newItems as $item)
        {
            if (isset($notProcessedItems[$item->getId()->toString()]))
            {
                unset($notProcessedItems[$item->getId()->toString()]);
                $elem = $this->getItem($item->getId());
                $elem=$item;
            }
            else
            {
                $this->addItem($item);
            }
        }

        foreach ($notProcessedItems as $item)
        {
            $this->removeItem($item);
        }
    }

    public function itemsAsArray(): array
    {
        $itemsArray = [];
        foreach ($this->getItems() as $item)
        {
            $itemsArray[$item->getId()->toString()] = $item;
        }

        return $itemsArray;    }

    /**
     * @throws ShoppingListItemNotFoundException
     */
    public function removeItem(ShoppingListItem $toBeRemoved): void
    {
        foreach ($this->getItems() as $k => $item)
        {
            if ($item->getId()->toString() === $toBeRemoved->getId()->toString())
            {
                unset($this->items[$k]);
                return;
            }
        }

        throw new ShoppingListItemNotFoundException($toBeRemoved->getId()->toString());
    }

    public function items(): iterable
    {
        return $this->items;
    }
}
