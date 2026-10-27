<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseQueryFactory;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Presentation\Http\ValueResolver\MapRouteParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetMealCourseInstance extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GetMealCourseQueryFactory $queryFactory
    ){}

    #[Route('/api/v1/meal-courses/{id}', name: 'meal_courses_get_instance', methods: ['GET'])]
    public function __invoke(#[MapRouteParameter] GetMealCourseDto $dto): JsonResponse
    {
        $query = $this->queryFactory->createFromDto($dto);
        $response = $this->queryBus->ask($query);

        return new JsonResponse($response);
    }
}
