<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\DataFixtures\Ingredient\AdditionalIngredientFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\IngredientType\Application\Service\IngredientTypeItemsPager;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateIngredientTest extends  AppApiTestCase
{
    private AdditionalIngredientFixtures $additionalIngredientFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientFixtures = new AdditionalIngredientFixtures(
            $this->container->get(IngredientTypeItemsPager::class)
        );
        $this->loadFixture($this->additionalIngredientFixtures);
    }

    #[Test]
    public function testCreateIngredient()
    {
        $this->assertNotEmpty($this->additionalIngredientFixtures->testIngredientTypes);
        $ingredientToCreateData = [
            'an ingredient',
            $this->additionalIngredientFixtures->testIngredientTypes[0]->getId()->toString(),
            'no description available'
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
