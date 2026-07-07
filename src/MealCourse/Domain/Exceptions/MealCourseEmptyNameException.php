<?php
namespace App\MealCourse\Domain\Exceptions;

use InvalidArgumentException;
use Throwable;

final class MealCourseEmptyNameException extends InvalidArgumentException
{
    public function __construct(readonly ?Throwable $previous = null)
    {
        parent::__construct('Meal course name cannot be empty', 0, $previous);
    }
}
