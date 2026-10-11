<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteIngredientTypeControllerTest extends AppApiTestCase
{
    private IngredientTypeFixtures $additionalIngredientTypeFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientTypeFixtures = new IngredientTypeFixtures();
        $this->loadFixture($this->additionalIngredientTypeFixtures);
    }

    #[Test]
    public function deleteIngredientType(): void
    {
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('ingredient_types_delete_instance', ['id' => $this->additionalIngredientTypeFixtures->testIngredientTypes[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure the ingredient type was not deleted
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_ingredient_type', ['id' => $this->additionalIngredientTypeFixtures->testIngredientTypes[0]->getId()->toString()]),
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
    public function deleteIngredientTypeNotFound(): void
    {
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('ingredient_types_delete_instance', ['id' => AggregateRootId::generateId()->toString()]),
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
    public function deleteIngredientTypeWrongId(): void
    {
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('ingredient_types_delete_instance', ['id' => 'Wrong-Id-Passed']),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_BAD_REQUEST);
    }
}
