<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Recipe\RecipeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetRecipeControllerTest extends AppApiTestCase
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
    public function it_gets_the_recipe():void
    {
        $id = $this->recipeFixtures->testRecipes[0]->getId();

        $response = $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipe', ['id' => $id]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertObjectHasProperty('recipeDto', $response);
        $recipeResponse = $response->recipeDto;
        $this->assertIsObject($recipeResponse);
        $this->assertObjectHasProperty('id', $recipeResponse);
        $this->assertEquals($id, $recipeResponse->id);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_id_is_not_found():void
    {
        $id = AggregateRootId::generateId();

        $response = $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipe', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized():void
    {
        $id = AggregateRootId::generateId();

        $response = $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipe', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
