<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseRouteDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseQueryFactory;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class GetMealCourseInstance extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetMealCourseQueryFactory $queryFactory,
        private readonly MealCourseResource $resource,
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_courses_get_instance', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetMealCourseRouteDto $routeDto): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($routeDto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->item($response->mealCourse));
    }
}
