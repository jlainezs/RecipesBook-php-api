<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Presentation\Http\Controller\DeleteMealCourseController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DeleteMealCourseControllerTest extends TestCase
{
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $id = AggregateRootId::generateId()->toString();

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->withAnyParameters();
        $validator->expects($this->once())
            ->method('validate')
            ->withAnyParameters();
        $controller = new DeleteMealCourseController($commandBus, $validator);

        $response = $controller($id);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
