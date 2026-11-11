<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQueryFactory;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\JsonApi\JsonApi;
use App\Shared\Presentation\Http\JsonApi\JsonApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetMealCoursesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetMealCoursesQueryFactory $queryFactory,
        private readonly MealCourseResource $resource
    ){}

    #[Route('/api/v1/meal-courses', name: 'meal_courses_list', defaults: [JsonApi::ROUTE_FLAG => true], methods: ['GET'])]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST)]
        GetMealCoursesDto $dto
    ): JsonApiResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonApiResponse($this->resource->collection($response->items, $query->offset, $query->limit));
    }
}
