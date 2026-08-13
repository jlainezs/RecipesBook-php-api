<?php
namespace App\Tests\Unit\ShoppingList\Domain\Exceptions;

use App\Shared\Domain\ValueObject\AggregateRootId;
use App\ShoppingList\Domain\Exceptions\ShoppingListNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ShoppingListNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $requiredId = AggregateRootId::generateId();
        $exception = new ShoppingListNotFoundException($requiredId);
        $expectedMsg = sprintf("Shopping list with id '%s' not found", $requiredId->toString());
        $this->assertEquals($expectedMsg, $exception->getMessage());
    }
}
