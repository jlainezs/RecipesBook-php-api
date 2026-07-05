<?php
namespace App\MealCourse\Domain\Exceptions;

use Exception;
use Throwable;

final class MealCourseEmptyNameException extends Exception
{
    public function __construct(readonly ?Throwable $previous = null)
    {
        parent::__construct('Meal type name cannot be empty', 0, $previous);
    }
}
