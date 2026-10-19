<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Tests\DataFixtures\Recipe\RecipeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteRecipeControllerTest extends AppApiTestCase
{
    public RecipeFixtures $recipeFixtures;

    public RecipeRepositoryInterface $recipeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->recipeFixtures = new RecipeFixtures();
        $this->loadFixture($this->recipeFixtures);
        $this->recipeRepository = $this->container->get(RecipeRepositoryInterface::class);
    }

    #[Test]
    public function it_should_delete_recipe():void
    {
        $recipeToDelete = $this->recipeFixtures->testRecipes[0];
        $this->client->request(
            "DELETE",
            $this->getDefinedUrl('delete_recipe', ['id' => $recipeToDelete->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure recipe is deleted
        $recipe = $this->recipeRepository->findOne($recipeToDelete->getId());
        $this->assertNull($recipe);
    }
}
