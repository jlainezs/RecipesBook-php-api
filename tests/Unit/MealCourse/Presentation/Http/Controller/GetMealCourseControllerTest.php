<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseResponse;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseRouteDto;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseQuery;
use App\MealCourse\Application\Query\MealCourse\GetMealCourse\GetMealCourseQueryFactory;
use App\MealCourse\Application\Query\MealCourse\MealCourseDto;
use App\MealCourse\Presentation\Http\Controller\GetMealCourseInstance;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetMealCourseControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $dto = new MealCourseDto(
            $id,
            'name',
            new  DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $queryBus = $this->createMock(QueryBus::class);
        $queryFactory = $this->createMock(GetMealCourseQueryFactory::class);
        $resource = new MealCourseResource($this->createStub(UrlGeneratorInterface::class));
        $request = new GetMealCourseRouteDto($id);

        $queryFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new GetMealCourseQuery($request->id));
        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters()
            ->willReturn(new GetMealCourseResponse($dto));
        $controller = new GetMealCourseInstance($queryBus, $queryFactory, $resource);
        $response = $controller($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
