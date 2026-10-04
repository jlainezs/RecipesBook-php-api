<?php
namespace App\Tests\Functional\Security\Users;

use App\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
    }

     #[Test]
    public function Login_will_succeed(): void
    {
        // act
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);

        // asserts
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertNotEmpty($responseData['token']);
    }

    #[Test]
    #[DataProvider('wrongCredentials')]
    public function login_with_wrong_credentials_will_fail(string $userName, string $password):void
    {
        // setup data
        $this->fixtureLoader->addFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->executor->execute($this->fixtureLoader->getFixtures());

        // act
        $this->loginWithUser($userName, $password);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertNotEmpty($responseData['message']);
        $this->assertEquals("Invalid credentials.", $responseData['message']);
        $this->assertArrayHasKey('code', $responseData);
        $this->assertNotEmpty($responseData['code']);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $responseData['code']);
    }

    public static function wrongCredentials(): iterable
    {
        yield 1 => [LoginFixtures::USER_NAME, 'wrong_password'];
        yield 2 => ['wrongUser@example.com', LoginFixtures::PASSWORD];
        yield 2 => ['wrongUser@example.com', 'wrong_password'];
        yield 2 => ['not_an_email', 'wrong_password'];
    }
}
