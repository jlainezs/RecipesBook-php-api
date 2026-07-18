<?php
namespace App\UnitOfMeasure\Domain\Model;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\Shared\Domain\ValueObject\RequiredName;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptyNameException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\ValueObjects\UnitOfMeasureSymbol;
use DateTimeImmutable;

final class UnitOfMeasure extends AggregateRoot
{
    private function __construct(
        private readonly AggregateRootId $id,
        private RequiredName $name,
        private UnitOfMeasureSymbol $symbol,
        private UnitOfMeasureEnum $uomType,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     * @throws UnitOfMeasureEmptyNameException
     * @throws UnitOfMeasureEmptySymbolException
     */
    public static function create(string $name, string $symbol, UnitOfMeasureEnum $unitOfMeasureType): self
    {
        if (empty(trim($symbol))) {
            throw new UnitOfMeasureEmptySymbolException();
        }

        return new self(
            AggregateRootId::generateId(),
            new RequiredName($name),
            new UnitOfMeasureSymbol($symbol),
            $unitOfMeasureType,
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
    }

    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @throws UnitOfMeasureEmptyNameException
     */
    public function rename(string $name): void
    {
        $this->name = new RequiredName($name);
    }

    public function getName(): string
    {
        return $this->name->value();
    }

    public function getSymbol(): UnitOfMeasureSymbol
    {
        return $this->symbol;
    }

    /**
     * @throws UnitOfMeasureEmptySymbolException
     * @throws UnitOfMeasureSymbolLengthException
     */
    public function changeSymbol(string $simbol): void
    {
        if (empty(trim($simbol))) {
            throw new UnitOfMeasureEmptySymbolException();
        }

        $this->symbol = new UnitOfMeasureSymbol($simbol);
    }

    public function getUomType(): UnitOfMeasureEnum
    {
        return $this->uomType;
    }

    public function setUomType(UnitOfMeasureEnum $uomType): void
    {
        $this->uomType = $uomType;
    }
}
