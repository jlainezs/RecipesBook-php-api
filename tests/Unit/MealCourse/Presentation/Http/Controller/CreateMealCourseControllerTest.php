<?php

namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseCommand;
use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseDto;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Presentation\Http\Controller\CreateMealCourseController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $cmdFactory = new CreateMealCourseCommandFactory($validator);
        $urlGeneratorInterface = $this->createMock(UrlGeneratorInterface::class);

        $id = AggregateRootId::generateId()->toString();
        $mealCourse = MealCourse::create(id:$id, name:'test');

        $validator->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (CreateMealCourseCommand $cmd) use ($mealCourse)
                {
                    return $cmd->name === $mealCourse->getName();
                }
            ));
        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (CreateMealCourseCommand $cmd) use ($mealCourse)
                {
                    return $cmd->name === $mealCourse->getName();
                }
            ));
        $urlGeneratorInterface->expects($this->once())
            ->method('generate')
            ->withAnyParameters();

        $controller = new CreateMealCourseController($commandBus, $cmdFactory, $urlGeneratorInterface);
        $request = new CreateMealCourseDto($mealCourse->getName());

        $response = $controller($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

    }
}
