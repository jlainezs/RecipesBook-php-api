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
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json'
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

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $response = json_decode($this->client->getResponse()->getContent());
        var_dump($response);
        $this->assertNotNull($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertNotEmpty($response->data->id);
        $expectedGetUrl = $this->getDefinedUrl('get_shopping-list', ['id' => $response->data->id]);
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
