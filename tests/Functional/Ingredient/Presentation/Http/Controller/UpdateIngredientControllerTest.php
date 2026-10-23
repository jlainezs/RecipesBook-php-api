<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateIngredientControllerTest extends AppApiTestCase
{
    private IngredientFixtures $ingredientFixtures;
    private IngredientTypeFixtures $ingredientTypeFixtures;
    private IngredientRepositoryInterface $ingredientRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->ingredientTypeFixtures = new IngredientTypeFixtures();
        $this->loadFixture($this->ingredientTypeFixtures);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
        $this->ingredientRepository = $this->container->get(IngredientRepositoryInterface::class);
    }

    #[Test]
    public function updateIngredient(): void
    {
        $id = $this->ingredientFixtures->testIngredients[0]->getId();
        $ingredient = $this->ingredientRepository->findOne($id);
        $this->assertNotNull($ingredient);

        $updatedData = [
            'name' => 'updated name',
            'description' => 'updated description',
            'ingredientTypeId' => $this->ingredientTypeFixtures->testIngredientTypes[0]->getId()->toString(),
        ];

        $this->assertNotEmpty($updatedData['ingredientTypeId']);

        $this->client->request(
            'PUT',
            $this->getDefinedUrl('ingredient_update_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($updatedData)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure updated
        $ingredient = $this->ingredientRepository->findOne($id);
        $this->assertEquals($updatedData['name'], $ingredient->getName());
        $this->assertEquals($updatedData['description'], $ingredient->getDescription());
    }

    #[Test]
    public function updateIngredientFailsWhenIngredientIsNotFound()
    {
        {
            $id = AggregateRootId::generateId();

            $updatedData = [
                'name' => 'updated name',
                'description' => 'updated description',
                'ingredientTypeId' => $this->ingredientTypeFixtures->testIngredientTypes[0]->getId()->toString(),
            ];

            $this->assertNotEmpty($updatedData['ingredientTypeId']);

            $this->client->request(
                'PUT',
                $this->getDefinedUrl('ingredient_update_instance', ['id' => $id->toString()]),
                [],
                [],
                [
                    'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT' => 'application/json',
                ],
                json_encode($updatedData)
            );
            $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
        }
    }

    #[Test]
    public function updateIngredientFailsWhenNoAuthorization()
    {
        {
            $id = AggregateRootId::generateId();

            $updatedData = [
                'name' => 'updated name',
                'description' => 'updated description',
                'ingredientTypeId' => $this->ingredientTypeFixtures->testIngredientTypes[0]->getId()->toString(),
            ];

            $this->assertNotEmpty($updatedData['ingredientTypeId']);

            $this->client->request(
                'PUT',
                $this->getDefinedUrl('ingredient_update_instance', ['id' => $id->toString()]),
                [],
                [],
                [
                    'CONTENT_TYPE' => 'application/json',
                    'HTTP_ACCEPT' => 'application/json',
                ],
                json_encode($updatedData)
            );
            $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
        }
    }
}
