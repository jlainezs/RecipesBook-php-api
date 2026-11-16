<?php
namespace App\Tests\Functional\Security\Presentation\Http\Controller;

use App\Security\Domain\Model\UserRole;
use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\AdditionalUsersFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
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

    private function patchDocument(AggregateRootId $id, array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json',
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            method: 'PATCH',
            uri: '/api/v1/users/' . $id->toString(),
            server: $headers,
            content: json_encode($document)
        );

    }

    #[Test]
    public function Update_user_with_valid_data()
    {
        $userToUpdate = $this->userRepository->findOneByEmail($this->additionalUsersFixtures->testUsers[0]->getEmail())->getId();
        $document = [
            'data' => [
                'type' => UserResource::TYPE,
                'id' => $userToUpdate->toString(),
                'attributes' => [
                    'email' => 'newemail@example.com',
                    'password' => 'newpassword',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'roles' => [UserRole::USER],
                ]
            ]
        ];

        $this->patchDocument($userToUpdate, $document);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function Update_user_with_invalid_role()
    {
        $userToUpdate = $this->userRepository->findOneByEmail($this->additionalUsersFixtures->testUsers[0]->getEmail())->getId();
        $document = [
            'data' => [
                'type' => UserResource::TYPE,
                'id' => $userToUpdate->toString(),
                'attributes' => [
                    'email' => 'newemail@example.com',
                    'password' => 'newpassword',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'roles' => ['Wrong role'],
                ]
            ]
        ];

        $this->patchDocument($userToUpdate, $document);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function Update_user_with_invalid_id()
    {
        $userToUpdate = AggregateRootId::generateId();
        $document = [
            'data' => [
                'type' => UserResource::TYPE,
                'id' => $userToUpdate->toString(),
                'attributes' => [
                    'email' => 'newemail@example.com',
                    'password' => 'newpassword',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'roles' => [UserRole::USER],
                ]
            ]
        ];

        $this->patchDocument($userToUpdate, $document, false);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
