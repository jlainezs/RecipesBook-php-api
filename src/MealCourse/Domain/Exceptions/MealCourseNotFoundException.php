<?php

namespace App\MealCourse\Domain\Exceptions;

use App\Shared\Domain\Exception\EntityNotFoundException;
use Exception;
use Throwable;

final class MealCourseNotFoundException extends EntityNotFoundException
{
    public function __construct(string $requiredId = "", readonly ?Throwable $previous = null)
    {
        $message = sprintf("Meal course with id %s was not found", $requiredId);
        parent::__construct($message, 0, $previous);
    }
}
