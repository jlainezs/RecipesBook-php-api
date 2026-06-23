<?php
namespace App\Shared\Domain\Model;

use App\Shared\Domain\Event\DomainEventInterface;
use DateTime;

abstract class AggregateRoot
{
    protected array $domainEvents;

    public DateTime $createdAt {
        get {
            return $this->createdAt;
        }
        set {
            $this->createdAt = $value;
        }
    }
    public DateTime $updatedAt {
        get {
            return $this->updatedAt;
        }
        set {
            $this->updatedAt = $value;
        }
    }

    public function addDomainEvent(DomainEventInterface $event): self {
        $this->domainEvents[] = $event;

        return $this;
    }

    public function pullDomainEvents(): array {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

}
