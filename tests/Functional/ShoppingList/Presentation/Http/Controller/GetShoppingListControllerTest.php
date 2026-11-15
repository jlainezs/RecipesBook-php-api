<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\ShoppingList\ShoppingListFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetShoppingListControllerTest extends AppApiTestCase
{
    public ShoppingListFixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new ShoppingListFixtures();
        $this->loadFixture($this->fixtures);
    }

    private function getDocument(AggregateRootId $id, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_shopping-list', ['id' => $id->toString()]),
            [],
            [],
            $headers
        );

    }

    #[Test]
    public function it_returns_the_required_shopping_list(): void
    {
        $this->getDocument($this->fixtures->testData[0]->getId());
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertSame('1.1', $response->jsonapi->version);
        $this->assertObjectHasProperty('data', $response);
        $this->assertIsObject($response->data);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertObjectHasProperty('type', $response->data);
        $this->assertSame(ShoppingListResource::TYPE, $response->data->type);
        $this->assertObjectHasProperty('attributes', $response->data);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_shopping_list_does_not_exist(): void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
