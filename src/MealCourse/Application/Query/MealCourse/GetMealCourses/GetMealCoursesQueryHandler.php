<?php
namespace App\MealCourse\Application\Query\MealCourse\GetMealCourses;

use App\MealCourse\Application\Query\MealCourse\MealCourseDto;
use App\MealCourse\Application\Service\MealCourseItemsPager;
use App\MealCourse\Domain\Model\MealCourse;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetMealCoursesQueryHandler
{
    public function __construct(private MealCourseItemsPager $list)
    {}

    public function __invoke(GetMealCoursesQuery $query): GetMealCoursesQueryResponse
    {
        $itemsDto = array_map(
            fn(MealCourse $t) => new MealCourseDto(
                $t->getId()->toString(),
                $t->getName(),
                $t->getCreatedAt(),
                $t->getUpdatedAt()
            ),
            $this->list->items($query->offset, $query->limit)
        );
        return new GetMealCoursesQueryResponse($itemsDto);
    }
}
