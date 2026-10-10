<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\DataFixtures\Ingredient\AdditionalIngredientTypeFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientTypesControllerTest extends AppApiTestCase
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
    public function getIngredientTypes(): void
    {
        $this->client->request(
            'GET', $this->getDefinedUrl('ingredient_types_list'),
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
        $this->assertSameSize($this->additionalIngredientTypeFixtures->testIngredientTypes, $responseData['items']);

        for ($i = 0; $i < count($responseData['items']); $i++) {
            $this->assertArrayHasKey('id', $responseData['items'][$i]);
            $this->assertArrayHasKey('name', $responseData['items'][$i]);
            $this->assertEquals($this->additionalIngredientTypeFixtures->testIngredientTypes[$i]->getId()->toString(), $responseData['items'][$i]['id']);
            $this->assertEquals($this->additionalIngredientTypeFixtures->testIngredientTypes[$i]->getName()->value(), $responseData['items'][$i]['name']);
        }
    }

    #[Test]
    public function getIngredientTypesWithoutAuthorizationFails(): void
    {
        $this->client->request(
            'GET', $this->getDefinedUrl('ingredient_types_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
