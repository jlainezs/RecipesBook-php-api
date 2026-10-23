<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\MealCourse\MealCourseFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetMealCourseControllerTest extends AppApiTestCase
{
    public MealCourseFixtures $mealCourseFixtures;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->mealCourseFixtures = new MealCourseFixtures();
        $this->loadFixture($this->mealCourseFixtures);
    }

    #[Test]
    public function it_should_return_the_required_meal_course()
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_get_instance', ["id" => $this->mealCourseFixtures->testMealCourses[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        $mealCourseResponse = json_decode($this->client->getResponse()->getContent(), true);
        $mealCourse = $mealCourseResponse['mealCourse'];
        $this->assertIsArray($mealCourse);
        $this->assertArrayHasKey('id', $mealCourse);
        $this->assertArrayHasKey('name', $mealCourse);
        $this->assertEquals($mealCourse["id"], $this->mealCourseFixtures->testMealCourses[0]->getId()->toString());
        $this->assertEquals($mealCourse["name"], $this->mealCourseFixtures->testMealCourses[0]->getName());
    }

    #[Test]
    public function it_should_fail_if_meal_course_id_does_not_exists()
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_get_instance', ["id" => AggregateRootId::generateId()->toString()]),
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
    public function it_should_fail_if_not_authorized()
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_get_instance', ["id" => $this->mealCourseFixtures->testMealCourses[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
