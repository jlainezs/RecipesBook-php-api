<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\ShoppingList\Domain\Repository\ShoppingListRepositoryInterface;
use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
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

    public function patchDocument(AggregateRootId $id, array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => self::CONTENT_TYPE_HEADER_JSON_API,
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'PATCH',
            $this->getDefinedUrl('put_shopping-list', ['id' => $id->toString()]),
            [],
            [],
            $headers,
            json_encode($document)
        );

    }

    #[Test]
    #[DataProvider('validLists')]
    public function it_updates_the_shopping_list(array $listData): void
    {
        $toUpdate = $this->fixtures->testData[0];
        $document = [
            'data' => [
                'type' => ShoppingListResource::TYPE,
                'id' => $toUpdate->getId()->toString(),
                'attributes' => [
                    'name' => $listData['name'],
                    'items' => $listData['items'],
                    'scheduledFor' => $toUpdate->getScheduledFor(),
                    'createdAt' => $toUpdate->getCreatedAt(),
                    'updatedAt' => $toUpdate->getUpdatedAt(),
                ]
            ]
        ];
        $this->patchDocument($toUpdate->getId(), $document);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

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
        $id = AggregateRootId::generateId();
        $toUpdate = $this->fixtures->testData[0];
        $document = [
            'data' => [
                'type' => ShoppingListResource::TYPE,
                'id' => $id->toString(),
                'attributes' => [
                    'name' => 'some data',
                    'items' => [],
                    'scheduledFor' => $toUpdate->getScheduledFor(),
                    'createdAt' => $toUpdate->getCreatedAt(),
                    'updatedAt' => $toUpdate->getUpdatedAt(),
                ]
            ]
        ];
        $this->patchDocument($id, $document);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $toUpdate = $this->fixtures->testData[0];
        $document = [
            'data' => [
                'type' => ShoppingListResource::TYPE,
                'id' => $id->toString(),
                'attributes' => [
                    'name' => 'some data',
                    'items' => [],
                    'scheduledFor' => $toUpdate->getScheduledFor(),
                    'createdAt' => $toUpdate->getCreatedAt(),
                    'updatedAt' => $toUpdate->getUpdatedAt(),
                ]
            ]
        ];
        $this->patchDocument($id, $document, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
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
