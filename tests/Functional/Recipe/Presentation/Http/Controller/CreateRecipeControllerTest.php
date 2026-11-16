<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateRecipeControllerTest extends AppApiTestCase
{
    private IngredientFixtures $ingredientFixtures;
    private UnitOfMeasureFixture $unitOfMeasureFixtures;
    private RecipeRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
        $this->unitOfMeasureFixtures = new UnitOfMeasureFixture();
        $this->loadFixture($this->unitOfMeasureFixtures);
        $this->repository = $this->container->get(RecipeRepositoryInterface::class);
    }

    private function testRecipe(): array
    {
        return [
            'name' => 'test recipe',
            'description' => 'test description',
            'rating' => 5,
            'servings' => 4,
            'ingredients' => [
                [
                    'ingredientId' => $this->ingredientFixtures->testData[0]->getId()->toString(),
                    'quantity' => 20,
                    'unitOfMeasureId' => $this->unitOfMeasureFixtures->testData[0]->getId()->toString(),
                    'ordering' => 1,
                ],
                [
                    'ingredientId' => $this->ingredientFixtures->testData[1]->getId()->toString(),
                    'quantity' => 10,
                    'unitOfMeasureId' => $this->unitOfMeasureFixtures->testData[1]->getId()->toString(),
                    'ordering' => 2,
                ],
            ],
            'steps' => [],
        ];
    }

    private function postDocument(array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $newRecipe = [
            'data' => [
                'type' => RecipeResource::TYPE,
                'attributes' => $document
            ]
        ];
        $this->client->request(
            'POST',
            $this->getDefinedUrl('post_recipe'),
            [],
            [],
            $headers,
            json_encode($newRecipe)
        );

    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_a_recipe(): void
    {
        $newRecipe = $this->testRecipe();
        $this->postDocument($newRecipe);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSame(RecipeResource::TYPE, $response->data->type);

        $this->assertObjectHasProperty('id', $response->data);
        $this->assertIsString($response->data->id);
        $this->assertNotEmpty($response->data->id);

        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('get_recipe', ['id' => $response->data->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);

        // ensure created recipe exists
        $recipe = $this->repository->findOne(new AggregateRootId($response->data->id));
        $this->assertNotNull($recipe);
        $this->assertEquals($newRecipe['name'], $recipe->getName());
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $newRecipe = $this->testRecipe();
        $this->postDocument($newRecipe, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_ingredient_not_found(): void
    {
        $newRecipe = $this->testRecipe();
        // add a non-existent ingredient to force an error
        $newRecipe['ingredients'][0]['ingredientId'] = AggregateRootId::generateId()->toString();
        $this->postDocument($newRecipe);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }
}
