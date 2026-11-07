<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
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
        $id = $this->ingredientFixtures->testData[0]->getId();
        $ingredient = $this->ingredientRepository->findOne($id);
        $this->assertNotNull($ingredient);

        $updatedData = [
            'data' => [
                'type' => IngredientResource::TYPE,
                'id' => $id->toString(),
                'attributes' => [
                    'name' => 'updated name',
                    'description' => 'updated description',
                    'ingredientTypeId' => $this->ingredientTypeFixtures->testData[0]->getId()->toString(),
                ]
            ]
        ];

        $this->patchDocument($id, $updatedData);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure updated
        $ingredient = $this->ingredientRepository->findOne($id);
        $this->assertEquals($updatedData['data']['attributes']['name'], $ingredient->getName());
        $this->assertEquals($updatedData['data']['attributes']['description'], $ingredient->getDescription());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function updateIngredientFailsWhenIngredientIsNotFound()
    {
        {
            $id = AggregateRootId::generateId();

            $updatedData = [
                'data' => [
                    'type' => IngredientResource::TYPE,
                    'id' => $id->toString(),
                    'attributes' => [
                        'name' => 'updated name',
                        'description' => 'updated description',
                        'ingredientTypeId' => $this->ingredientTypeFixtures->testData[0]->getId()->toString(),
                    ]
                ]
            ];

            $this->patchDocument($id, $updatedData);

            $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function updateIngredientFailsWhenNoAuthorization()
    {
        {
            $id = AggregateRootId::generateId();

            $updatedData = [
                'data' => [
                    'type' => IngredientResource::TYPE,
                    'id' => $id->toString(),
                    'attributes' => [
                        'name' => 'updated name',
                        'description' => 'updated description',
                        'ingredientTypeId' => $this->ingredientTypeFixtures->testData[0]->getId()->toString(),
                    ]
                ]
            ];

            $this->patchDocument($id, $updatedData, false);

            $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
        }
    }


    private function patchDocument(AggregateRootId $id, array $document, bool $sendAuth = true): void
    {
        $contentType = 'application/vnd.api+json';
        $accept = 'application/vnd.api+json';
        $route =$this->getDefinedUrl('ingredient_update_instance', ['id' => $id->toString()]);

        $headers = [
            'CONTENT_TYPE' => $contentType,
            'HTTP_ACCEPT' => $accept
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'PATCH',
            $route,
            [],
            [],
            $headers,
            json_encode($document)
        );
    }
}
