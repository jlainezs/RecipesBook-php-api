<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\DeleteMealCourse\DeleteMealCourseDto;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DeleteMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly DeleteMealCourseCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_courses_delete_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['DELETE'])]
    public function __invoke(#[MapRouteParameter] DeleteMealCourseDto $dto): Response
    {
        $cmd = $this->cmdFactory->createFromDto($dto);
        $this->commandBus->dispatch($cmd);

        return new Response(status: Response::HTTP_NO_CONTENT);
    }
}
