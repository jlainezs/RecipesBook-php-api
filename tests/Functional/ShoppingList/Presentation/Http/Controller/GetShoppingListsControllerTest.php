<?php
namespace App\Tests\Functional\ShoppingList\Presentation\Http\Controller;

use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\ShoppingList\ShoppingListFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetShoppingListsControllerTest extends AppApiTestCase
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

    public function getDocument(bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_shopping-lists_list'),
            [],
            [],
            $headers
        );

    }

    #[Test]
    public function it_returns_a_list_of_shopping_lists():void
    {
        $this->getDocument();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);
    }

    #[Test]
    public function fails_when_not_authorized(): void
    {
        $this->getDocument(false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
