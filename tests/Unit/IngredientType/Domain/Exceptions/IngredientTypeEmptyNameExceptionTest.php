<?php

namespace App\Tests\Unit\IngredientType\Domain\Exceptions;

use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class IngredientTypeEmptyNameExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new IngredientTypeEmptyNameException();

        $this->assertSame('IngredientType name is empty', $exception->getMessage());
    }
}
