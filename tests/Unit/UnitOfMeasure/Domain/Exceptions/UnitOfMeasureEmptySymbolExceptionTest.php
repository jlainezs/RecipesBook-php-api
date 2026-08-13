<?php

namespace App\Tests\Unit\UnitOfMeasure\Domain\Exceptions;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureEmptySymbolExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $exception = new UnitOfMeasureEmptySymbolException();
        $this->assertEquals('Unit of measure symbol cannot be empty', $exception->getMessage());
    }
}
