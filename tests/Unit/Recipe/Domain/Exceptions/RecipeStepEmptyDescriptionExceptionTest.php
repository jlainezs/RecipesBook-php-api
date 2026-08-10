<?php
namespace App\Tests\Unit\Recipe\Domain\Exceptions;

use App\Recipe\Domain\Exceptions\RecipeStepEmptyDescriptionException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeStepEmptyDescriptionExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new RecipeStepEmptyDescriptionException();
        $this->assertSame('Recipe step description cannot be empty', $exception->getMessage());
    }
}
