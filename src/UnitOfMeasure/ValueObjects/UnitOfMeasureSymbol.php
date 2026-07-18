<?php
namespace App\UnitOfMeasure\ValueObjects;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Infrastructure\Doctrine\Type\UnitOfMeasureSymbolType;

final readonly class UnitOfMeasureSymbol
{
    private string $value;

    /**
     * @throws UnitOfMeasureEmptySymbolException
     * @throws UnitOfMeasureSymbolLengthException
     */
    public function __construct(string $value)
    {
        if (empty(trim($value))) {
            throw new UnitOfMeasureEmptySymbolException();
        }

        if (strlen($value) > UnitOfMeasureSymbolType::DEFAULT_LENGTH) {
            throw new UnitOfMeasureSymbolLengthException();
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
