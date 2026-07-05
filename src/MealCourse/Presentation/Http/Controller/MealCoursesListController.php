<?php
namespace App\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\MealCoursesQuery;
use App\MealCourse\Presentation\Http\Response\MealCoursesListJsonResponse;
use App\Shared\Application\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MealCoursesListController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ){}

    #[Route('/api/v1/meal-courses', name: 'meal_courses_list', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $response = $this->queryBus->ask(new MealCoursesQuery(
            $request->query->get('page', 0),
            $request->query->get('limit', 20),
        ));

        return MealCoursesListJsonResponse::create($response->items);
    }

}
