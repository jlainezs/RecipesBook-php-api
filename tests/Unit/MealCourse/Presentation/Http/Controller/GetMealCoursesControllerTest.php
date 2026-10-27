<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQuery;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQueryFactory;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQueryResponse;
use App\MealCourse\Presentation\Http\Controller\GetMealCoursesController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetMealCoursesControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private GetMealCoursesQueryFactory $queryFactory;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->queryFactory = $this->createMock(GetMealCoursesQueryFactory::class);
    }

    #[Test]
    public function it_returns_meal_course_list(): void
    {
        $request = new GetMealCoursesDto(
            offset: 0,
            limit: 10
        );
        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function (GetMealCoursesQuery $query): bool {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ))
            ->willReturn(new GetMealCoursesQueryResponse([]));
        $this->queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetMealCoursesQuery(offset: $request->offset, limit: $request->limit));
        $controller = new GetMealCoursesController($this->queryBus, $this->queryFactory);

        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());

    }
}
