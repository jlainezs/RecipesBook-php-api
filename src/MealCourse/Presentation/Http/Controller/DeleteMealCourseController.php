<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommand;
use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteMealCourseCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_courses_delete_instance', methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteMealCourseDto $dto): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
