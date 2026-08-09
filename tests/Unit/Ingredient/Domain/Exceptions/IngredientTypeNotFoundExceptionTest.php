<?php

namespace App\Tests\Unit\Ingredient\Domain\Exceptions;

use App\Ingredient\Domain\Exceptions\IngredientTypeNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientTypeNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $ingredientTypeId = 'the_id';
        $exception = new IngredientTypeNotFoundException($ingredientTypeId);
        $expectedMessage = sprintf('Ingredient type with id "%s" not found', $ingredientTypeId);
        $this->assertSame($expectedMessage, $exception->getMessage());
    }
}
