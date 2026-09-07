<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQuery;
use App\MealCourse\Presentation\Http\Response\MealCoursesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

final class GetMealCoursesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly ApplicationDataValidator $validator
    ){}

    #[Route('/api/v1/meal-courses', name: 'meal_courses_list', methods: ['GET'])]
    public function __invoke(#[MapQueryString] GetMealCoursesDto $dto): JsonResponse
    {
        $query = new GetMealCoursesQuery(
            offset: $dto->offset,
            limit: $dto->limit,
        );
        $this->validator->validate($query);
        $response = $this->queryBus->ask($query);

        return MealCoursesListJsonResponse::create($response->items);
    }

}
