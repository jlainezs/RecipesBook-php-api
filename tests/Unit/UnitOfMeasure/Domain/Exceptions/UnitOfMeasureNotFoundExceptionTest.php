<?php

namespace App\Tests\Unit\UnitOfMeasure\Domain\Exceptions;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UnitOfMeasureNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $requestedId = AggregateRootId::generateId()->toString();
        $exception = new UnitOfMeasureNotFoundException($requestedId);
        $expectedMessage = sprintf('Unit of measure with id "%s" not found', $requestedId);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }
}
