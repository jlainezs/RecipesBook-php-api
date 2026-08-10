<?php
namespace App\Tests\Unit\Recipe\Domain\Exceptions;

use App\Recipe\Domain\Exceptions\RecipeEmptyNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeEmptyNameExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $exception = new RecipeEmptyNameException();
        $this->assertEquals('Recipe name cannot be empty', $exception->getMessage());
    }
}
