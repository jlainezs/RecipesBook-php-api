<?php
namespace App\Tests\Unit\Recipe\Domain\Exceptions;

use App\Recipe\Domain\Exceptions\RecipeIngredientInvalidQuantityException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeIngredientInvalidQuantityExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $exception = new RecipeIngredientInvalidQuantityException();
        $this->assertEquals('Recipe ingredient quantity is invalid', $exception->getMessage());
    }
}
