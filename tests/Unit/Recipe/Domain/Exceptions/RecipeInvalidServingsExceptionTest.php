<?php
namespace App\Tests\Unit\Recipe\Domain\Exceptions;

use App\Recipe\Domain\Exceptions\RecipeInvalidServingsException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeInvalidServingsExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $requiredServings = -1;
        $exception = new RecipeInvalidServingsException($requiredServings);
        $this->assertEquals(sprintf("Invalid servings. Servings must be greater than 1. Required %s", $requiredServings), $exception->getMessage());
    }
}
