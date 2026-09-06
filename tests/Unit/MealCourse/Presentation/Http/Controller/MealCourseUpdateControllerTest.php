<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\MealCourseUpdateCommand;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\MealCourseUpdateDto;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Presentation\Http\Controller\MealCourseUpdateController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
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
        $controller = new MealCourseUpdateController($this->commandBus, $this->validator);
        $request = new MealCourseUpdateDto($mealCourse->getName());
        $response = $controller($mealCourse->getId()->toString(), $request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
