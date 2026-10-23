<?php

namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

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
        $id = $this->ingredientFixtures->testIngredients[0]->getId()->toString();
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
        $this->assertResponseIsSuccessful();
        $ingredientDto = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals($id, $ingredientDto['ingredientDto']['id']);
    }

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
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function get_ingredient_fails_when_no_token_present():void
    {
        $id = $this->ingredientFixtures->testIngredients[0]->getId()->toString();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_get_instance', ['id' => $id]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
