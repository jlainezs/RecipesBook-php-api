<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Tests\DataFixtures\MealCourse\MealCourseFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetMealCoursesControllerTest extends AppApiTestCase
{
    private MealCourseFixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new MealCourseFixtures();
        $this->loadFixture($this->fixtures);
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
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);

        foreach ($response->data as $resource)
        {
            $this->assertSame(MealCourseResource::TYPE, $resource->type);
            $this->assertObjectHasProperty('id', $resource);
            $this->assertObjectHasProperty('attributes', $resource);
            $this->assertObjectHasProperty('name', $resource->attributes);
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
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
