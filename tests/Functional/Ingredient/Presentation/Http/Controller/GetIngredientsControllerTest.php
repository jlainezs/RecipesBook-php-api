<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientsControllerTest extends AppApiTestCase
{
    private IngredientFixtures $ingredientFixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
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
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
        $this->assertArrayHasKey('items', $responseData);
        $this->assertNotEmpty($responseData['items']);

        foreach($responseData['items'] as $item)
        {
            $this->assertArrayHasKey('id', $item);
            $this->assertArrayHasKey('name', $item);
            $this->assertArrayHasKey('description', $item);
            $this->assertArrayHasKey('description', $item);
        }
    }

    #[Test]
    public function getIngredientsFailsWhenNoToken(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_types_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
