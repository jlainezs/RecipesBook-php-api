<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\ShoppingList\ShoppingListFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteShoppingListControllerTest extends AppApiTestCase
{
    public ShoppingListFixtures $fixtures;
    public ShoppingListRepositoryInterface $shoppingListRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new ShoppingListFixtures();
        $this->loadFixture($this->fixtures);
        $this->shoppingListRepository = $this->container->get(ShoppingListRepositoryInterface::class);
    }

    #[Test]
    public function it_deletes_a_shopping_list(): void
    {
        $id = $this->fixtures->testData[0]->getId();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('shopping-lists_delete_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        // ensure deleted
        $shoppingList = $this->shoppingListRepository->findOne($id);
        $this->assertNull($shoppingList);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_item_does_not_exist(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('shopping-lists_delete_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('shopping-lists_delete_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
