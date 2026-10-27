<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseDto;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseRouteDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateMealCourseCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_course_update', methods: ['PUT'])]
    public function __invoke(
        #[MapRouteParameter] UpdateMealCourseRouteDto $routeDto,
        #[MapRequestPayload] UpdateMealCourseDto      $dto
    ): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromRequest($routeDto, $dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
