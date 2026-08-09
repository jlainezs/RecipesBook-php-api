<?php

namespace App\Tests\Unit\Ingredient\Domain\Exceptions;

use App\Ingredient\Domain\Exceptions\IngredientNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientNotFoundExceptionTest extends TestCase
{
    #[Test]
    function it_has_a_descriptive_message(): void
    {
        $ingredientId = 'the_id';
        $exception = new IngredientNotFoundException($ingredientId);
        $expectedMessage = sprintf("Ingredient %s not found.", $ingredientId);
        $this->assertSame($expectedMessage, $exception->getMessage());
    }
}
