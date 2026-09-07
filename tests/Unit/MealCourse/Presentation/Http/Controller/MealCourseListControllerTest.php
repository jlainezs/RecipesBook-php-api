<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQuery;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQueryResponse;
use App\MealCourse\Presentation\Http\Controller\MealCoursesListController;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class MealCourseListControllerTest extends TestCase
{
    private QueryBus $queryBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    #[Test]
    public function it_returns_meal_course_list(): void
    {
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
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (GetMealCoursesQuery $query): bool {
                    return $query->offset >= 0
                        && $query->limit > 0;
                }
            ));
        $controller = new MealCoursesListController($this->queryBus, $this->validator);
        $request = new GetMealCoursesDto(
            offset: 0,
            limit: 10
        );
        $response = $controller($request);

        $this->assertEquals(200, $response->getStatusCode());

    }
}
