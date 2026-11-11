<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\MealCourse\MealCourseFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetMealCourseControllerTest extends AppApiTestCase
{
    public MealCourseFixtures $fixtures;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new MealCourseFixtures();
        $this->loadFixture($this->fixtures);
    }

    private function getDocument(AggregateRootId $id, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_get_instance', ["id" => $id->toString()]),
            [],
            [],
            $headers
        );

    }

    #[Test]
    public function it_should_return_the_required_meal_course()
    {
        $this->getDocument($this->fixtures->testData[0]->getId());
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertSame('1.1', $response->jsonapi->version);

        $this->assertObjectHasProperty('data', $response);
        $this->assertIsObject($response->data);
        $this->assertSame(MealCourseResource::TYPE, $response->data->type);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertObjectHasProperty('attributes', $response->data);
        $this->assertObjectHasProperty('name', $response->data->attributes);
        $this->assertEquals($response->data->id, $this->fixtures->testData[0]->getId()->toString());
        $this->assertEquals($response->data->attributes->name, $this->fixtures->testData[0]->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_fail_if_meal_course_id_does_not_exists()
    {
        $this->getDocument(AggregateRootId::generateId());
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }
    #[Test]
    public function it_should_fail_if_not_authorized()
    {
        $this->getDocument($this->fixtures->testData[0]->getId(), false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
