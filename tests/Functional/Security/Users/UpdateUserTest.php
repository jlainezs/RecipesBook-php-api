<?php
namespace App\Tests\Functional\Security\Users;

use App\DataFixtures\Security\AdditionalUsersFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\Security\Domain\Model\UserRole;
use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserTest extends AppApiTestCase
{
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
    public function Update_user_with_valid_data()
    {
        $userToUpdate = $this->userRepository->findOneByEmail($this->additionalUsersFixtures->testUsers[0]->getEmail())->getId()->toString();
        $userToUpdateDto = [
            'email' => 'newemail@example.com',
            'password' => 'newpassword',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => [UserRole::USER],
        ];
        $content = json_encode($userToUpdateDto);

        // update the user
        $this->client->request(
            method: 'PUT', uri: '/api/v1/users/' . $userToUpdate,
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: $content
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function Update_user_with_invalid_role()
    {
        $userToUpdate = $this->userRepository->findOneByEmail($this->additionalUsersFixtures->testUsers[0]->getEmail())->getId()->toString();
        $userToUpdateDto = [
            'email' => 'newemail@example.com',
            'password' => 'newpassword',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => "wrong_role",
        ];
        $content = json_encode($userToUpdateDto);

        // update the user
        $this->client->request(
            method: 'PUT', uri: '/api/v1/users/' . $userToUpdate,
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: $content
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function Update_user_with_invalid_id()
    {
        $userToUpdate = AggregateRootId::generateId()->toString();
        $userToUpdateDto = [
            'email' => 'newemail@example.com',
            'password' => 'newpassword',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => [UserRole::USER],
        ];
        $content = json_encode($userToUpdateDto);

        // update the user
        $this->client->request(
            method: 'PUT', uri: '/api/v1/users/' . $userToUpdate,
            server: [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            content: $content
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
