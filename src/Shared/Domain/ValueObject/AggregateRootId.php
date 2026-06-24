<?php
namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

readonly class AggregateRootId
{
    private Uuid $id;

    /**
     * Creates a new AggregateRootId using as ID the given value
     *
     * @throws EmptyIdNotAllowedException
     */
    public function __construct(private string $value)
    {
        if (empty($this->value))
        {
            throw new EmptyIdNotAllowedException();
        }

        Assert::Uuid($value);
        $this->id = new Uuid($value);
    }

    /**
     * Creates a new instance of the AggregateRootId and generates a new ID.
     *
     * @return self
     * @throws EmptyIdNotAllowedException
     */
    public static function generateId(): self
    {
        $newId = Uuid::v4();
        return new self($newId->toString());
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
