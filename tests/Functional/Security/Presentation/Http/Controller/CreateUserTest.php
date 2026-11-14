<?php

namespace App\Tests\Functional\Security\Presentation\Http\Controller;

use App\Security\Domain\Model\UserRole;
use App\Security\Presentation\Http\Resource\UserResource;
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

    private function postDocument(array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'POST',
            $this->getDefinedUrl('users_create'),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
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
        $document = [
            'data' => [
                'type' => UserResource::TYPE,
                'attributes' => $userToCreateData
            ]
        ];
        $this->postDocument($document);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertIsObject($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertIsString($response->data->id);
        $this->assertNotEmpty($response->data->id);
        $this->assertObjectHasProperty('type', $response->data);
    }
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized()
    {
        $userToCreateData = [
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'roles' => [UserRole::USER],
        ];
        $document = [
            'data' => [
                'type' => UserResource::TYPE,
                'attributes' => $userToCreateData
            ]
        ];
        $this->postDocument($document, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function Create_user_does_not_allow_duplicate_email()
    {
        $user1 = [
            'data' => [
                'type' => UserResource::TYPE,
                'attributes' => [
                    'email' => 'john.doe@example.com',
                    'password' => 'password',
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'roles' => [UserRole::USER],
                ]
            ]
        ];
        $user2 = [
            'data' => [
                'type' => UserResource::TYPE,
                'attributes' => [
                    'email' => 'john.doe@example.com',
                    'password' => 'password',
                    'firstName' => 'Jane',
                    'lastName' => 'Doe',
                    'roles' => [UserRole::USER],
                ]
            ]
        ];
        $this->postDocument($user1);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $this->postDocument($user2);
        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
    }
}
