<?php

namespace App\Tests\Unit\UnitOfMeasure\Domain\Exceptions;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptyNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureEmptyNameExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new UnitOfMeasureEmptyNameException();
        $this->assertEquals('Unit of measure name cannot be empty', $exception->getMessage());
    }
}
