<?php

namespace App\Tests\Unit\UnitOfMeasure\Domain\Exceptions;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureSymbolLengthException;
use App\UnitOfMeasure\Infrastructure\Doctrine\Type\UnitOfMeasureSymbolType;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureSymbolLengthExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new UnitOfMeasureSymbolLengthException();
        $message = sprintf('Unit of measure symbol length must be less than or equal to %s' , UnitOfMeasureSymbolType::DEFAULT_LENGTH);
        $this->assertEquals($message, $exception->getMessage());
    }
}
