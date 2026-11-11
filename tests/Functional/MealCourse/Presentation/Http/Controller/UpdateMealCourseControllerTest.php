<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\MealCourse\Presentation\Http\Resource\MealCourseResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\MealCourse\MealCourseFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateMealCourseControllerTest extends AppApiTestCase
{
    private MealCourseFixtures $mealCourseFixtures;
    private MealCourseRepositoryInterface $mealCourseRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->mealCourseFixtures = new MealCourseFixtures();
        $this->loadFixture($this->mealCourseFixtures);
        $this->mealCourseRepository = $this->container->get(MealCourseRepositoryInterface::class);
    }

    private function patchDocument(AggregateRootId $id, array $document, bool $sendAuth = true): void
    {
        $requestData = [
            'data' => [
                'type' => MealCourseResource::TYPE,
                'id' => $id->toString(),
                'attributes' => $document,
            ]
        ];
        $headers = [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json',
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'PATCH',
            $this->getDefinedUrl('meal_course_update', ['id' => $id->toString()]),
            [],
            [],
            $headers,
            json_encode($requestData)
        );
    }

    #[Test]
    public function it_should_update_meal_course()
    {
        $id = $this->mealCourseFixtures->testData[0]->getId();
        $updatedData = ['name' => 'Updated Name'];
        $this->patchDocument($id, $updatedData);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $mealCourse = $this->mealCourseRepository->findOne($id);
        $this->assertNotNull($mealCourse);
        $this->assertEquals($updatedData['name'], $mealCourse->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_found()
    {
        $data = ['name' => 'Updated Name'];
        $id = AggregateRootId::generateId();
        $this->patchDocument($id, $data);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authenticated()
    {
        $data = ['name' => 'Updated Name'];
        $id = AggregateRootId::generateId();
        $this->patchDocument($id, $data, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
