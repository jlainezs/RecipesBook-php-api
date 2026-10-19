<?php

namespace App\Tests\Functional\Security\Users;

use App\Security\Domain\Model\UserRole;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\DataFixtures\Security\AdditionalUsersFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTest extends AppApiTestCase
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
    public function Create_user_with_valid_data()
    {
        $userToCreateData = [
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => [UserRole::USER],
        ];
        $this->client->request(
            'POST',
            '/api/v1/users/create',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($userToCreateData)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    #[Test]
    public function Create_user_does_not_allow_duplicate_email()
    {
        $userToCreateData = [
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => [UserRole::USER],
        ];
        $anotherToCreateData = [
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'roles' => [UserRole::USER],
        ];
        $this->client->request(
            'POST',
            '/api/v1/users/create',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($userToCreateData)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->client->request(
            'POST',
            '/api/v1/users/create',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($anotherToCreateData)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
