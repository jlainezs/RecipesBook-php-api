<?php
namespace App\Tests\Functional\Security\Users;

use App\DataFixtures\Security\AdditionalUsersFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteUserTest extends AppApiTestCase
{
    protected string $userToken;
    protected string $userId;
    protected AdditionalUsersFixtures $additionalUsersFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->additionalUsersFixtures = new AdditionalUsersFixtures($this->container->get(UserPasswordHasherInterface::class));
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loadFixture($this->additionalUsersFixtures);
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    #[Test]
    public function Delete_user_removes_it(): void
    {
        $userToDelete = $this->userRepository->findOneByEmail($this->additionalUsersFixtures->testUsers[0]->getEmail())->getId()->toString();
        $userToKeep = $this->userRepository->findOneByEmail($this->additionalUsersFixtures->testUsers[1]->getEmail())->getId()->toString();
        // delete one user
        $this->client->request(
            method: 'DELETE', uri: '/api/v1/users/' . $userToDelete,
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // check the required user is deleted
        $this->client->request(
            method: 'GET', uri: '/api/v1/users/' . $this->additionalUsersFixtures->testUsers[0]->getId()->toString(),
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        // check another user is still there
        $this->client->request(
            method: 'GET', uri: '/api/v1/users/' . $userToKeep,
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    #[Test]
    public function Delete_user_with_invalid_token_fails(): void
    {
        $this->client->request(
            method: 'DELETE', uri: '/api/v1/users/' . $this->additionalUsersFixtures->testUsers[0]->getId()->toString(),
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function Delete_user_with_invalid_id_fail(): void
    {
        $id = AggregateRootId::generateId()->toString();
        $this->client->request(
            method: 'DELETE', uri: '/api/v1/users/' . $id,
            server: ['HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
