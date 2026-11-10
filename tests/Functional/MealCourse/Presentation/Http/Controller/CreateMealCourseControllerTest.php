<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Tests\DataFixtures\Security\LoginFixtures;
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

    private function postDocument(array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request('POST', $this->getDefinedUrl('meal_courses_create'),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    #[Test]
    public function it_should_create_meal_course(): void
    {
        $mealCourse = [
            'data' => [
                'type' => MealCourseResource::TYPE,
                'attributes' => [
                    'name' => 'test meal course'
                ]
            ]
        ];
        $this->postDocument($mealCourse);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->assertIsObject($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertIsString($response->data->id);
        $this->assertNotEmpty($response->data->id);
        $this->assertObjectHasProperty('type', $response->data);
    }

    #[Test]
    public function it_should_fail_when_name_is_empty(): void
    {
        $mealCourse = [
            'data' => [
                'type' => MealCourseResource::TYPE,
                'attributes' => [
                    'name' => ''
                ]
            ]
        ];
        $this->postDocument($mealCourse);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    #[Test]
    public function it_should_fail_when_no_authorization_sent(): void
    {
        $mealCourse = [
            'data' => [
                'type' => MealCourseResource::TYPE,
                'attributes' => [
                    'name' => 'test meal course'
                ]
            ]
        ];
        $this->postDocument($mealCourse, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
