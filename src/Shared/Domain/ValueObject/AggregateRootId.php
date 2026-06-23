<?php
namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use Symfony\Component\Uid\Ulid;
use Webmozart\Assert\Assert;

readonly class AggregateRootId
{
    private Ulid $id;

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

        Assert::uuid($value);
        $this->id = new Ulid($value);
    }

    /**
     * Creates a new instance of the AggregateRootId and generates a new ID.
     *
     * @return self
     * @throws EmptyIdNotAllowedException
     */
    public static function generateId(): self
    {
        $newId = Ulid::generate();
        return new self($newId);
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }
}
