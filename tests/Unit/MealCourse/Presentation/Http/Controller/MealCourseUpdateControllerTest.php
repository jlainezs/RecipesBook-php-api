<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\MealCourseUpdateCommand;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Presentation\Http\Controller\PutMealCourseController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class MealCourseUpdateControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $mealCourse = MealCourse::create('test');
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (MealCourseUpdateCommand $cmd) use ($mealCourse)
                {
                    return $cmd->id === $mealCourse->getId()->toString();
                }
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (MealCourseUpdateCommand $cmd) use ($mealCourse)
                {
                    return $cmd->id === $mealCourse->getId()->toString();
                }
            ));
        $controller = new PutMealCourseController($this->commandBus, $this->validator);
        $payload = ['name' => 'test'];
        $request = Request::create(
            uri: '/api/v1/meal-courses/' . $mealCourse->getId()->toString(),
            method: 'PUT',
            server: ['Content-Type' => 'application/json'],
            content: json_encode($payload)
        );
        $request->attributes->set('id', $mealCourse->getId()->toString());

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
