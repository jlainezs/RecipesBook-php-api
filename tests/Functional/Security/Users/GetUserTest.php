<?php
namespace App\Tests\Functional\Security\Users;

use App\DataFixtures\Security\LoginFixtures;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Shared\Domain\ValueObjects\Email;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetUserTest extends AppApiTestCase
{
    protected UserRepositoryInterface $userRepository;
    protected string $userToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->userToken = $this->getAuthToken();
    }

    #[Test]
    public function get_the_required_user():void
    {
        $userId = $this->userRepository->findOneByEmail(new Email(LoginFixtures::USER_NAME))->getId()->toString();
        $this->client->request(
            method: 'GET', uri: '/api/v1/users/' . $userId,
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('user', $responseData);
        $user = $responseData['user'];
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertEquals(LoginFixtures::USER_NAME, $user['email']);
        $this->assertArrayHasKey('firstName', $user);
        $this->assertEquals(LoginFixtures::FIRST_NAME, $user['firstName']);
        $this->assertArrayHasKey('lastName', $user);
        $this->assertEquals(LoginFixtures::LAST_NAME, $user['lastName']);
        $this->assertArrayHasKey('roles', $user);
    }

    #[Test]
    public function when_requesting_wrong_user_it_fails():void
    {
        $userId = AggregateRootId::generateId()->toString();
        $this->client->request(
            method: 'GET', uri: '/api/v1/users/' . $userId,
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
