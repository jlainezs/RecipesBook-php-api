<?php
namespace App\Tests\Functional\MealCourse\Presentation\Http\Controller;

use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\MealCourse\MealCourseFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteMealCourseControllerTest extends AppApiTestCase
{
    private MealCourseFixtures $fixtures;
    private MealCourseRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new MealCourseFixtures();
        $this->loadFixture($this->fixtures);
        $this->repository = $this->container->get(MealCourseRepositoryInterface::class);
    }

    private function deleteDocument(string $id, bool $useAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API,
        ];
        if ($useAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('meal_courses_delete_instance', ['id' => $id]),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function it_should_delete_meal_course(): void
    {
        $this->deleteDocument($this->fixtures->testData[0]->getId()->toString());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure the meal course was deleted
        $mealCourse = $this->repository->findOne($this->fixtures->testData[0]->getId());
        $this->assertNull($mealCourse);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_fail_when_meal_course_does_not_exists(): void
    {
        $this->deleteDocument(AggregateRootId::generateId()->toString());
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_should_fail_when_not_authorized(): void
    {
        $this->deleteDocument($this->fixtures->testData[0]->getId()->toString(), false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
