<?php
namespace App\Tests\Unit\MealCourse\Domain\Exceptions;

use App\MealCourse\Domain\Exceptions\MealCourseEmptyNameException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MealCourseEmptyNameExceptionTest extends TestCase
{
    #[Test]
    public function it_has_a_descriptive_message(): void
    {
        $exception = new MealCourseEmptyNameException();

        $this->assertSame('Meal course name cannot be empty', $exception->getMessage());
    }
}
