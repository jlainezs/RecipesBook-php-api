<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Presentation\Http\Resource\IngredientResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientControllerTest extends AppApiTestCase
{
    private IngredientFixtures $ingredientFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
    }

    #[Test]
    public function get_ingredient():void
    {
        $id = $this->ingredientFixtures->testData[0]->getId()->toString();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_get_instance', ['id' => $id]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertJsonApiGetResponse(Response::HTTP_OK, IngredientResource::TYPE);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($this->ingredientFixtures->testData[0]->getId()->toString(), $response->data->id);
        $this->assertEquals($this->ingredientFixtures->testData[0]->getName(), $response->data->attributes->name);
        $this->assertObjectHasProperty('ingredientTypeId', $response->data->attributes);
        $this->assertObjectHasProperty('description', $response->data->attributes);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function get_ingredient_fails_when_id_is_not_found():void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_get_instance', ['id' => $id]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function get_ingredient_fails_when_no_token_present():void
    {
        $id = $this->ingredientFixtures->testData[0]->getId()->toString();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_get_instance', ['id' => $id]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
