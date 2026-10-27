<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommand;
use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseDto;
use App\MealCourse\Presentation\Http\Controller\DeleteMealCourseController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteMealCourseControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(DeleteMealCourseCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();
        $request = new DeleteMealCourseDto($id);

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $cmdFactory->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new DeleteMealCourseCommand($request->id));
        $controller = new DeleteMealCourseController($commandBus, $cmdFactory);

        $response = $controller($request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
