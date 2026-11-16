<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteIngredientTypeControllerTest extends AppApiTestCase
{
    private IngredientTypeFixtures $fixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new IngredientTypeFixtures();
        $this->loadFixture($this->fixtures);
    }

    private function deleteDocument(string $id, bool $useAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];
        if ($useAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('ingredient_types_delete_instance', ['id' => $id]),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function it_deletes_ingredient_type(): void
    {
        $this->deleteDocument($this->fixtures->testData[0]->getId()->toString());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure the ingredient type was not deleted
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_ingredient_type', ['id' => $this->fixtures->testData[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_ingredient_type_does_not_exist(): void
    {
        $this->deleteDocument(AggregateRootId::generateId()->toString());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_fails_when_ingredient_type_id_is_wrong(): void
    {
        $this->deleteDocument('wrong id');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    public function it_fails_when_user_not_authenticated(): void
    {
        $this->deleteDocument('wrong id', false);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
