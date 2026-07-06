<?php

namespace App\Tests\Unit\MealCourse\Domain\Exceptions;

use App\MealCourse\Domain\Exceptions\MealCourseNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class MealCourseNotFoundExceptionTest extends TestCase
{
    #[Test]
    public function it_includes_the_requested_id_in_the_message(): void
    {
        $id = '3fa85f64-5717-4562-b3fc-2c963f66afa6';
        $exception = new MealCourseNotFoundException($id);

        $this->assertStringContainsString($id, $exception->getMessage());
    }

    #[Test]
    public function it_uses_a_default_empty_id_when_none_provided(): void
    {
        $exception = new MealCourseNotFoundException();

        $this->assertStringContainsString('', $exception->getMessage());
    }
}
