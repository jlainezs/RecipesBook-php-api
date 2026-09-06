<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\MealCourseUpdateCommand;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\MealCourseUpdateDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class MealCourseUpdateController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_course_update', methods: ['PUT'])]
    public function __invoke(string $id, #[MapRequestPayload] MealCourseUpdateDto $dto): JsonResponse
    {
        $cmd = new MealCourseUpdateCommand($id, $dto->name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return new JsonResponse(null, 204);
    }
}
