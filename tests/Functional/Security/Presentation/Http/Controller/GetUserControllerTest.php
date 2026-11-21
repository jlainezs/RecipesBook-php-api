<?php
namespace App\Tests\Functional\Security\Presentation\Http\Controller;

use App\Security\Presentation\Http\Resource\UserResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
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
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
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
        $document = $this->assertJsonApiGetResponse(Response::HTTP_OK, UserResource::TYPE);
        $this->assertObjectHasProperty('email', $document->data->attributes);

        $this->assertObjectHasProperty('email', $document->data->attributes);
        $this->assertEquals(LoginFixtures::USER_NAME,  $document->data->attributes->email);
        $this->assertObjectHasProperty('firstName',  $document->data->attributes);
        $this->assertEquals(LoginFixtures::FIRST_NAME, $document->data->attributes->firstName);
        $this->assertObjectHasProperty('lastName',  $document->data->attributes);
        $this->assertEquals(LoginFixtures::LAST_NAME, $document->data->attributes->lastName);
        $this->assertObjectHasProperty('roles',  $document->data->attributes);
    }

    #[Test]
    public function it_fails_when_not_authorized():void
    {
        $userId = AggregateRootId::generateId()->toString();
        $this->getDocument($userId, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_found():void
    {
        $userId = AggregateRootId::generateId()->toString();
        $this->getDocument($userId);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }
}
