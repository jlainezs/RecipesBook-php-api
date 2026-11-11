<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
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

    private function getDocument(AggregateRootId $id, bool $sendAuth = true): void
    {
        $headers = ['HTTP_ACCEPT' => 'application/vnd.api+json'];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipe', ['id' => $id]),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function it_gets_the_recipe():void
    {
        $id = $this->recipeFixtures->testRecipes[0]->getId();
        $this->getDocument($id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertSame('1.1', $response->jsonapi->version);
        $this->assertObjectHasProperty('data', $response);
        $this->assertIsObject($response->data);
        $this->assertObjectHasProperty('type', $response->data);
        $this->assertSame(RecipeResource::TYPE, $response->data->type);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertEquals($id, $response->data->id);
        $this->assertObjectHasProperty('attributes', $response->data);
        $recipeResponse = $response->data->attributes;
        var_dump($response->data);
        $this->assertIsObject($recipeResponse);
        $this->assertObjectHasProperty('self', $response->links);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_id_is_not_found():void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized():void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
