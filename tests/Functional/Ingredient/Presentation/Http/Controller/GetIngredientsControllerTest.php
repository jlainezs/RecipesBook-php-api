<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientsControllerTest extends AppApiTestCase
{
    private IngredientFixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new IngredientFixtures();
        $this->loadFixture($this->fixtures);
    }

    #[Test]
    public function getIngredients(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_list'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);

        foreach($response->data as $resource)
        {
            $this->assertSame(IngredientResource::TYPE, $resource->type);
            $this->assertObjectHasProperty('id', $resource);
            $this->assertObjectHasProperty('name', $resource->attributes);
            $this->assertObjectHasProperty('description', $resource->attributes);
        }
        $this->assertSame(
            $this->getDefinedUrl('ingredient_list', ['page' => ['offset' => 0, 'limit' => 20]]),
            $response->links->first
        );
        $this->assertNull($response->links->prev);
        $this->assertNull($response->links->next);
    }

    #[Test]
    public function getIngredientsFailsWhenNoToken(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ],
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
