<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\MealCourseUpdateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PutMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_course_update', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $id = $request->attributes->getString('id');
        $cmd = new MealCourseUpdateCommand($id, $name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return $this->json(null, 204);
    }
}
