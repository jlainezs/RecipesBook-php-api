<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\MealCourseCreateCommand;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PostMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/meal-courses/create', name: 'meal_courses_create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $name = $request->getPayload()->getString('name');
        $cmd = new MealCourseCreateCommand($name);
        $this->validator->validate($cmd);
        $this->commandBus->dispatch($cmd);

        return $this->json(null, 201);
    }
}
