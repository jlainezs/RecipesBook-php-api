<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseQuery;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseQueryFactory;
use App\MealCourse\Presentation\Http\Controller\GetMealCourseInstance;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class GetMealCourseControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetMealCourseQueryFactory::class);
        $dto = new GetMealCourseDto($id);

        $queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetMealCourseQuery($dto->id));
        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters();
        $controller = new GetMealCourseInstance($queryBus, $queryFactory);
        $response = $controller($dto);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
