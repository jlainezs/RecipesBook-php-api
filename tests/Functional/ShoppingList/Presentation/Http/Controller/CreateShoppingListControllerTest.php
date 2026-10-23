<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateShoppingListControllerTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    #[Test]
    #[DataProvider('validLists')]
    public function it_creates_a_shopping_list(array $testData): void
    {
        $this->client->request(
            'POST',
            $this->getDefinedUrl('shopping_lists_create'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($testData)
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertObjectHasProperty('id', $response);
        $this->assertNotEmpty($response->id);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('get_shopping-list', ['id' => $response->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
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
