<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Recipe\RecipeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateRecipeControllerTest extends AppApiTestCase
{
    public RecipeFixtures $recipeFixtures;
    private UnitOfMeasureFixture $unitOfMeasureFixtures;
    public RecipeRepositoryInterface $recipeRepository;
    public IngredientFixtures $ingredientFixtures;

    private function testRecipe(): array
    {
        return [
            'name' => 'test recipe updated',
            'description' => 'test description updated',
            'rating' => 5,
            'servings' => 4,
            'ingredients' => [
                [
                    'ingredientId' => $this->ingredientFixtures->testIngredients[0]->getId()->toString(),
                    'quantity' => 20,
                    'unitOfMeasureId' => $this->unitOfMeasureFixtures->testData[0]->getId()->toString(),
                    'ordering' => 1,
                ],
                [
                    'ingredientId' => $this->ingredientFixtures->testIngredients[1]->getId()->toString(),
                    'quantity' => 10,
                    'unitOfMeasureId' => $this->unitOfMeasureFixtures->testData[1]->getId()->toString(),
                    'ordering' => 2,
                ],
            ],
            'steps' => [],
        ];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->recipeFixtures = new RecipeFixtures();
        $this->loadFixture($this->recipeFixtures);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
        $this->unitOfMeasureFixtures = new UnitOfMeasureFixture();
        $this->loadFixture($this->unitOfMeasureFixtures);
        $this->recipeRepository = $this->container->get(RecipeRepositoryInterface::class);
    }

    #[Test]
    public function it_updates_the_recipe(): void
    {
        $id = $this->recipeFixtures->testRecipes[0]->getId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('update_recipe', ['id' => $id->toString()]),
            [],[],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->testRecipe())
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure updated
        $updatedRecipe = $this->recipeRepository->findOne($id);
        $this->assertEquals($this->testRecipe()['name'], $updatedRecipe->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_recipe_does_not_exist(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('update_recipe', ['id' => $id->toString()]),
            [],[],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->testRecipe())
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('update_recipe', ['id' => $id->toString()]),
            [],[],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode($this->testRecipe())
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
