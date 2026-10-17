<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\DataFixtures\MealCourse\MealCourseFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteMealCourseControllerTest extends AppApiTestCase
{
    private MealCourseFixtures $mealCourseFixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->mealCourseFixtures = new MealCourseFixtures();
        $this->loadFixture($this->mealCourseFixtures);
    }

    #[Test]
    public function it_should_delete_meal_course(): void
    {
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('meal_courses_delete_instance', ['id' => $this->mealCourseFixtures->testMealCourses[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure the meal course was deleted
        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_get_instance', ['id' => $this->mealCourseFixtures->testMealCourses[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_should_fail_when_meal_course_does_not_exists(): void
    {
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('meal_courses_delete_instance', ['id' => AggregateRootId::generateId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }
    #[Test]
    public function it_should_fail_when_not_authorized(): void
    {
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('meal_courses_get_instance', ['id' => $this->mealCourseFixtures->testMealCourses[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
