<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQuery;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQueryFactory;
use App\MealCourse\Application\Query\MealCourse\GetMealCourses\GetMealCoursesQueryResponse;
use App\MealCourse\Presentation\Http\Controller\GetMealCoursesController;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Query\PaginatedList\PageDto;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $request = new GetMealCoursesDto(new PageDto(
            offset: 0,
            limit: 10
        ));
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
            ->willReturn(new GetMealCoursesQuery(offset: $request->page->offset, limit: $request->page->limit));
        $controller = new GetMealCoursesController(
            $this->queryBus,
            $this->queryFactory,
            new MealCourseResource(
                $this->createStub(UrlGeneratorInterface::class)
            )
        );

        $response = $controller($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $document = json_decode($response->getContent(), true);
        $this->assertSame([], $document['data']);
    }
}
