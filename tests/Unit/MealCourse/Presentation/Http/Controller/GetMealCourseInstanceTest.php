<?php
namespace App\Tests\Unit\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Application\Query\MealCourse\MealCourseInstanceQuery;
use App\MealCourse\Domain\Model\MealCourse;
use App\MealCourse\Presentation\Http\Controller\GetMealCourseInstance;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GetMealCourseInstanceTest extends TestCase
{
    #[Test]
    public function it_validates_dispatches_query_and_returns_200(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $mealCourse = MealCourse::create('test');
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (MealCourseInstanceQuery $query) use ($mealCourse): bool {
                    return $query->id === $mealCourse->getId()->toString();
                }
            ));
        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->withAnyParameters();
        $controller = new GetMealCourseInstance($queryBus, $validator);
        $response = $controller($mealCourse->getId()->toString());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
