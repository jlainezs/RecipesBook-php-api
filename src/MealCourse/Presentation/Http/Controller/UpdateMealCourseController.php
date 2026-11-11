<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseCommandFactory;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseDto;
use App\MealCourse\Application\Command\MealCourse\UpdateMealCourse\UpdateMealCourseRouteDto;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\ValueResolver\MapJsonApiPayload;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateMealCourseController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UpdateMealCourseCommandFactory $cmdFactory
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_course_update', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['PATCH'])]
    public function __invoke(
        #[MapRouteParameter]
        UpdateMealCourseRouteDto $routeDto,
        #[MapJsonApiPayload(MealCourseResource::TYPE)]
        UpdateMealCourseDto $dto
    ): Response
    {
        $cmd = $this->cmdFactory->createFromRequest($routeDto, $dto);
        $this->commandBus->dispatch($cmd);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
