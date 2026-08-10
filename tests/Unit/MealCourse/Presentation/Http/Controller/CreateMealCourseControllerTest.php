<?php

namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\MealCourseCreateCommand;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Presentation\Http\Controller\PostMealCourseController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateMealCourseControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $mealCourse = MealCourse::create(name:'test');

        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (MealCourseCreateCommand $cmd) use ($mealCourse)
                {
                    return $cmd->name === $mealCourse->getName();
                }
            ));
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (MealCourseCreateCommand $cmd) use ($mealCourse)
                {
                    return $cmd->name === $mealCourse->getName();
                }
            ));
        $controller = new PostMealCourseController($commandBus, $validator);
        $request = Request::create(
            uri:'/api/v1/meal-courses/create',
            method:'POST',
            server: ['Content-Type' => 'application/json'],
            content: json_encode(['name' => $mealCourse->getName()])
        );

        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());

    }
}
