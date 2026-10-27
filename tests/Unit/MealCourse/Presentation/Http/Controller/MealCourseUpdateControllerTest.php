<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseCommand;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseDto;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseRouteDto;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Presentation\Http\Controller\UpdateMealCourseController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class MealCourseUpdateControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private UpdateMealCourseCommandFactory $cmdFactory;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->cmdFactory = $this->createMock(UpdateMealCourseCommandFactory::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $mealCourse = MealCourse::create($id, 'test');
        $requestRoute = new UpdateMealCourseRouteDto($mealCourse->getId()->toString());
        $requestBody = new UpdateMealCourseDto($mealCourse->getName());

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (UpdateMealCourseCommand $cmd) use ($mealCourse)
                {
                    return $cmd->id === $mealCourse->getId()->toString();
                }
            ));
        $this->cmdFactory
            ->expects($this->once())
            ->method('createFromRequest')
            ->withAnyParameters()
            ->willReturn(new UpdateMealCourseCommand($requestRoute->id, $requestBody->name));
        /*
            ->with($this->callback(
                function (UpdateMealCourseCommand $cmd) use ($mealCourse)
                {
                    return $cmd->id === $mealCourse->getId()->toString();
                }
            ));*/
        $controller = new UpdateMealCourseController($this->commandBus, $this->cmdFactory);
        $response = $controller($requestRoute, $requestBody);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
