<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseDto;
use App\MealCourse\Application\Command\MealCourse\CreateMealCourse\CreateMealCourseResponse;
use App\Shared\Application\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CreateMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly CreateMealCourseCommandFactory $cmdFactory,
        private readonly UrlGeneratorInterface $urlGenerator,
    ){}

    #[Route('/api/v1/meal-courses', name: 'meal_courses_create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateMealCourseDto $dto): JsonResponse
    {
        $cmd = $this->cmdFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);
        $response = new CreateMealCourseResponse($cmd->id);

        return new JsonResponse(
            data: $response,
            status: Response::HTTP_CREATED,
            headers: [
                'Content-Type' => 'application/json',
                'Location' => $this->urlGenerator->generate('meal_courses_get_instance', ['id' => $cmd->id], UrlGeneratorInterface::ABSOLUTE_URL),
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
}
