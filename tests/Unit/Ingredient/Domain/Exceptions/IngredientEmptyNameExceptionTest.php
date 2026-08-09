<?php
namespace App\Tests\Unit\Ingredient\Domain\Exceptions;

use App\Ingredient\Domain\Exceptions\IngredientEmptyNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientEmptyNameExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new IngredientEmptyNameException();
        $this->assertSame('Ingredient name is empty', $exception->getMessage());
    }
}
