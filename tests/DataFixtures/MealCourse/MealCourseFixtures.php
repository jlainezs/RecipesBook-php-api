<?php
namespace App\Tests\DataFixtures\MealCourse;

use App\MealCourse\Domain\Model\MealCourse;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MealCourseFixtures extends Fixture
{
    public array $testMealCourses;

    public function __construct()
    {
        $this->testMealCourses = [];
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public  function load(ObjectManager $manager): void
    {
        $rawObjects = [
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "Test Meal Course 1",
            ],
            [
                "id" => AggregateRootId::generateId()->toString(),
                "name" => "Test Meal Course 2",
            ],
        ];

        foreach ($rawObjects as $rawObject) {
            $mealCourse = MealCourse::create(
                $rawObject["id"],
                $rawObject["name"]
            );
            $this->testMealCourses[] = $mealCourse;
            $manager->persist($mealCourse);
        }

        $manager->flush();
    }

}
