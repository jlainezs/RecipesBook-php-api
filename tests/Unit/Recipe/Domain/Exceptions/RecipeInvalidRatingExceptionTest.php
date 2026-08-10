<?php
namespace App\Tests\Unit\Recipe\Domain\Exceptions;

use App\Recipe\Domain\Exceptions\RecipeInvalidRatingException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RecipeInvalidRatingExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message()
    {
        $requiredRating = -1;
        $exception = new RecipeInvalidRatingException($requiredRating);
        $this->assertEquals(sprintf("Invalid rating. Rating must be between 1 and 5. Required %s", $requiredRating), $exception->getMessage());
    }
}
