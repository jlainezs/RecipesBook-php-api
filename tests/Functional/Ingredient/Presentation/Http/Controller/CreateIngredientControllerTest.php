<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\DataFixtures\Ingredient\AdditionalIngredientTypeFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateIngredientControllerTest extends  AppApiTestCase
{
    private AdditionalIngredientTypeFixtures $additionalIngredientTypeFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientTypeFixtures = new AdditionalIngredientTypeFixtures();
        $this->loadFixture($this->additionalIngredientTypeFixtures);
    }

    #[Test]
    public function testCreateIngredient()
    {
        $this->assertNotEmpty($this->additionalIngredientTypeFixtures->testIngredientTypes);
        $ingredientToCreateData = [
            'name' =>'an ingredient',
            'ingredientTypeId' => $this->additionalIngredientTypeFixtures->testIngredientTypes[0]->getId()->toString(),
            'description' =>'no description available'
        ];
        $this->client->request(
            'POST',
            '/api/v1/ingredients/create',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($ingredientToCreateData)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
