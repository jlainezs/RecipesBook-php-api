<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\MealCoursesQuery;
use App\MealCourse\Application\Query\MealCourse\MealCoursesQueryResponse;
use App\MealCourse\Presentation\Http\Controller\MealCoursesListController;
use App\Shared\Application\Bus\QueryBus;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class MealCourseListControllerTest extends TestCase
{
    private QueryBus $queryBus;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    #[Test]
    public function it_returns_meal_course_list(): void
    {
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function (MealCoursesQuery $query): bool {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ))
            ->willReturn(new MealCoursesQueryResponse([]));
        $controller = new MealCoursesListController($this->queryBus);
        $request = Request::create(
            uri: '/api/v1/meal-courses?offset=0&limit=10',
            server: ['Content-Type' => 'application/json']
        );

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());

    }
}
