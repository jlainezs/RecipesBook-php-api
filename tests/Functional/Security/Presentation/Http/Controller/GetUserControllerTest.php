<?php
namespace App\Tests\Functional\Security\Users;

use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetUserControllerTest extends AppApiTestCase
{
    protected string $userToken;
    protected LoginFixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->fixtures = new LoginFixtures($this->container->get(UserPasswordHasherInterface::class));
        $this->loadFixture($this->fixtures);
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->userToken = $this->getAuthToken();
    }

    private function getDocument(string $id, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('users_get_instance', ['id' => $id]),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function get_the_required_user():void
    {
        $this->getDocument($this->fixtures->user->getId()->toString());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        var_dump($response);
        $this->assertIsObject($response);
        $this->assertSame('1.1', $response->jsonapi->version);
        $this->assertObjectHasProperty('data', $response);
        $this->assertIsObject($response->data);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertObjectHasProperty('type', $response->data);
        $this->assertSame(UserResource::TYPE, $response->data->type);
        $this->assertObjectHasProperty('attributes', $response->data);
        $this->assertIsObject($response->data->attributes);
        $this->assertObjectHasProperty('email', $response->data->attributes);

        $this->assertObjectHasProperty('attributes', $response->data);
        $this->assertIsObject($response->data->attributes);

        $this->assertObjectHasProperty('email', $response->data->attributes);
        $this->assertEquals(LoginFixtures::USER_NAME,  $response->data->attributes->email);
        $this->assertObjectHasProperty('firstName',  $response->data->attributes);
        $this->assertEquals(LoginFixtures::FIRST_NAME, $response->data->attributes->firstName);
        $this->assertObjectHasProperty('lastName',  $response->data->attributes);
        $this->assertEquals(LoginFixtures::LAST_NAME, $response->data->attributes->lastName);
        $this->assertObjectHasProperty('roles',  $response->data->attributes);
    }

    #[Test]
    public function when_requesting_wrong_user_it_fails():void
    {
        $userId = AggregateRootId::generateId()->toString();
        $this->getDocument($userId, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
