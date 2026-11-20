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
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ]
        );
        $this->assertJsonApiGetCollectionResponse(
            Response::HTTP_OK, MealCourseResource::TYPE,
            [
                'self' => $this->getDefinedUrl('meal_courses_list', ['page' => ['offset' => 0, 'limit' => 20]]),
                'first' => $this->getDefinedUrl('meal_courses_list', ['page' => ['offset' => 0, 'limit' => 20]]),
                'prev' => null,
                'next' => null,
            ]
        );
        $document = json_decode($this->client->getResponse()->getContent());
        $this->assertSameSize($this->fixtures->testData, $document->data);

        foreach ($document->data as $resource)
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
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
