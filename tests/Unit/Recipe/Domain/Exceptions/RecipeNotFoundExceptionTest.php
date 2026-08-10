<?php
namespace App\Tests\Unit\Recipe\Domain\Exceptions;

use App\Recipe\Domain\Exceptions\RecipeNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $recipeId = 1;
        $exception = new RecipeNotFoundException($recipeId);
        $this->assertEquals(sprintf("Recipe %s not found.", $recipeId), $exception->getMessage());
    }
}
