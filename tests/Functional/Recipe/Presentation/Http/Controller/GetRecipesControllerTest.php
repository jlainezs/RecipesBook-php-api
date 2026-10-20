<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Tests\DataFixtures\Recipe\RecipeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetRecipesControllerTest extends AppApiTestCase
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
    public function it_should_return_a_list_of_recipes():void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipes_list'),
            [],[],
            [
                'HTTP_Authorization' => 'Bearer '.$this->userToken,
                'Accept' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertIsArray($response->items);
        $this->assertCount(count($this->recipeFixtures->testRecipes), $response->items);
    }

    #[Test]
    public function it_should_fail_when_not_authorized(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipes_list'),
            [],[],
            [
                'Accept' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
