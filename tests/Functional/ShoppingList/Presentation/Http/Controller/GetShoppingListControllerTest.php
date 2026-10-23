<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
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

    #[Test]
    public function it_returns_the_required_shopping_list(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_shopping-list', ['id' => $this->fixtures->testData[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertObjectHasProperty('shoppingListDto', $response);
        $this->assertEquals($this->fixtures->testData[0]->getId()->toString(), $response->shoppingListDto->id);
        $this->assertSameSize($this->fixtures->testData[0]->getItems(), $response->shoppingListDto->items);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_shopping_list_does_not_exist(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_shopping-list', ['id' => $id]),
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

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_shopping-list', ['id' => $id]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
