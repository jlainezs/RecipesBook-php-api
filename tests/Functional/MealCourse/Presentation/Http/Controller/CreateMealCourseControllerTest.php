<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateMealCourseControllerTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    #[Test]
    public function it_should_create_meal_course(): void
    {
        $mealCoure = [
            'name' => 'test meal course'
        ];
        $this->client->request('POST', $this->getDefinedUrl('meal_courses_create'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($mealCoure)
        );
        $mealCourseResponse = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $mealCourseResponse);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('meal_courses_get_instance', ['id' => $mealCourseResponse['id']]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
    }

    #[Test]
    public function it_should_fail_when_name_is_empty(): void
    {
        $mealCoure = [
            'name' => ''
        ];
        $this->client->request('POST', $this->getDefinedUrl('meal_courses_create'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($mealCoure)
        );
        $this->assertProblemDetailsResponse(Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    public function it_should_fail_when_no_authorization_sent(): void
    {
        $mealCoure = [
            'name' => ''
        ];
        $this->client->request('POST', $this->getDefinedUrl('meal_courses_create'),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($mealCoure)
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
