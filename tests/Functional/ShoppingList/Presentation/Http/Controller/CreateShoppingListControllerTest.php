<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\ShoppingList\Presentation\Http\Resource\ShoppingListResource;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateShoppingListControllerTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    public function postDocument(array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => self::CONTENT_TYPE_HEADER_JSON_API,
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API,
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'POST',
            $this->getDefinedUrl('shopping_lists_create'),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    #[Test]
    #[DataProvider('validLists')]
    public function it_creates_a_shopping_list(array $testData): void
    {
        $this->postDocument($testData);
        $document = $this->assertJsonApiCreateResponse(Response::HTTP_CREATED, ShoppingListResource::TYPE);
        $expectedGetUrl = $this->getDefinedUrl('get_shopping-list', ['id' => $document->data->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
    }

    public static function validLists(): iterable
    {
        yield 'noItems' => [
            [ 'data' =>
                [
                    'type' => ShoppingListResource::TYPE,
                    'attributes' => [
                        'name' => 'sample with no items',
                        'items' => [],
                    ]
                ]
            ],
        ];
    }
}
