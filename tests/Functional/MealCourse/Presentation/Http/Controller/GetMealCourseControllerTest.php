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

    private function getDocument(mixed $id, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API,
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('meal_courses_get_instance', ["id" => $id]),
            [],
            [],
            $headers
        );

    }

    #[Test]
    public function it_should_return_the_required_meal_course()
    {
        $this->getDocument($this->fixtures->testData[0]->getId());
        $document = $this->assertJsonApiGetResponse(Response::HTTP_OK, MealCourseResource::TYPE);
        $this->assertEquals($document->data->id, $this->fixtures->testData[0]->getId()->toString());
        $this->assertEquals($document->data->attributes->name, $this->fixtures->testData[0]->getName());
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

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_fail_if_meal_course_id_is_wrong()
    {
        $this->getDocument('wrong_id');
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    public function it_should_fail_if_not_authorized()
    {
        $this->getDocument($this->fixtures->testData[0]->getId(), false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
