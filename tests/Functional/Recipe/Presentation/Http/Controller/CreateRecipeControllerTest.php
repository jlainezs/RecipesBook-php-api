<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
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
    private RecipeRepositoryInterface $recipeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
        $this->unitOfMeasureFixtures = new UnitOfMeasureFixture();
        $this->loadFixture($this->unitOfMeasureFixtures);
        $this->recipeRepository = $this->container->get(RecipeRepositoryInterface::class);
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
                    'ingredientId' => $this->ingredientFixtures->testIngredients[0]->getId()->toString(),
                    'quantity' => 20,
                    'unitOfMeasureId' => $this->unitOfMeasureFixtures->testUnitOfMeasures[0]->getId()->toString(),
                    'ordering' => 1,
                ],
                [
                    'ingredientId' => $this->ingredientFixtures->testIngredients[1]->getId()->toString(),
                    'quantity' => 10,
                    'unitOfMeasureId' => $this->unitOfMeasureFixtures->testUnitOfMeasures[1]->getId()->toString(),
                    'ordering' => 2,
                ],
            ],
            'steps' => [],
        ];
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_a_recipe(): void
    {
        $newRecipe = $this->testRecipe();

        $this->client->request(
            'POST',
            $this->getDefinedUrl('post_recipe'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($newRecipe)
        );

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $response);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('get_recipe', ['id' => $response['id']]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);

        // ensure created recipe exists
        $recipe = $this->recipeRepository->findOne(new AggregateRootId($response['id']));
        $this->assertNotNull($recipe);
        $this->assertEquals($newRecipe['name'], $recipe->getName());
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $newRecipe = $this->testRecipe();

        $this->client->request(
            'POST',
            $this->getDefinedUrl('post_recipe'),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($newRecipe)
        );

        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_ingredient_not_found(): void
    {
        $newRecipe = $this->testRecipe();
        $newRecipe['ingredients'][0]['ingredientId'] = AggregateRootId::generateId()->toString();

        $this->client->request(
            'POST',
            $this->getDefinedUrl('post_recipe'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($newRecipe)
        );
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }
}
