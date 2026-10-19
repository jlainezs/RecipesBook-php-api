<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\MealCourse\Domain\Repository\MealCourseRepositoryInterface;
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

    #[Test]
    public function it_should_update_meal_course()
    {
        $id = $this->mealCourseFixtures->testMealCourses[0]->getId();
        $updatedData = ['name' => 'Updated Name'];
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('meal_course_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($updatedData)
        );
        $this->assertResponseIsSuccessful();
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
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('meal_course_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['name' => 'Updated Name'])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authenticated()
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('meal_course_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode(['name' => 'Updated Name'])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
