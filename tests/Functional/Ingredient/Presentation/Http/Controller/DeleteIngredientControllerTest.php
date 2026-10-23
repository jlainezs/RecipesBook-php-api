<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteIngredientControllerTest extends AppApiTestCase
{
    private IngredientTypeFixtures $additionalIngredientTypeFixtures;
    private IngredientFixtures $ingredientFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientTypeFixtures = new IngredientTypeFixtures();
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->additionalIngredientTypeFixtures);
        $this->loadFixture($this->ingredientFixtures);
    }

    #[Test]
    public function shouldDeleteIngredient(): void
    {
        $ingredientId = $this->ingredientFixtures->testIngredients[0]->getId()->toString();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('ingredient_delete', ['id' => $ingredientId]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(204);

        $this->client->request(
            'GET',
            '/api/v1/ingredients/' . $ingredientId,
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }
}
