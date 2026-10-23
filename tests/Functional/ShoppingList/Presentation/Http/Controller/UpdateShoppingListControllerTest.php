<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\ShoppingList\ShoppingListFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateShoppingListControllerTest extends AppApiTestCase
{
    public ShoppingListFixtures $fixtures;
    public ShoppingListRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new ShoppingListFixtures();
        $this->loadFixture($this->fixtures);
        $this->repository = $this->container->get(ShoppingListRepositoryInterface::class);
    }

    #[Test]
    #[DataProvider('validLists')]
    public function it_updates_the_shopping_list(array $listData): void
    {
        $toUpdate = $this->fixtures->testData[0];
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('put_shopping-list', ['id' => $toUpdate->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($listData)
        );

        $updated = $this->repository->findOne($toUpdate->getId());
        $this->assertNotNull($updated);
        $this->assertEquals($listData['name'], $updated->getName()->value());
        $this->assertSameSize($listData['items'], $updated->getItems());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function fails_when_shopping_list_not_found(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('put_shopping-list', ['id' => $id]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode([
                'name' => 'sample with no items',
                'items' => [],
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('put_shopping-list', ['id' => $id]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode([
                'name' => 'sample with no items',
                'items' => [],
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }

    public static function validLists(): iterable
    {
        yield 'noItems' => [
            [
                'name' => 'sample with no items',
                'items' => [],
            ],
        ];
    }

}
