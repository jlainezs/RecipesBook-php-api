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
        // act
        $this->loginWithUser($userName, $password);
        $responseData = $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals("Bad credentials", $responseData['title']);
    }

    public static function wrongCredentials(): iterable
    {
        yield 1 => [LoginFixtures::USER_NAME, 'wrong_password'];
        yield 2 => ['wrongUser@example.com', LoginFixtures::PASSWORD];
        yield 2 => ['wrongUser@example.com', 'wrong_password'];
        yield 2 => ['not_an_email', 'wrong_password'];
    }
}
