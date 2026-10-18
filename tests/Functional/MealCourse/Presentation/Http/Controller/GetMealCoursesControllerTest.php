<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\DataFixtures\MealCourse\MealCourseFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetMealCoursesControllerTest extends AppApiTestCase
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
    public function getMealCourses(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_list'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('items', $responseData);
        $this->assertSameSize($this->mealCourseFixtures->testMealCourses, $responseData['items']);

        for ($i = 0; $i < count($responseData['items']); $i++)
        {
            $this->assertArrayHasKey('id', $responseData['items'][$i]);
            $this->assertArrayHasKey('name', $responseData['items'][$i]);
        }
    }

    #[Test]
    public function it_fails_when_not_authenticated(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
