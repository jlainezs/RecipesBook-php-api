<?php

namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Season\SeasonFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetSeasonControllerTest extends AppApiTestCase
{
    private SeasonFixtures $seasonFixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->seasonFixtures = new SeasonFixtures();
        $this->loadFixture($this->seasonFixtures);
    }

    #[Test]
    public function it_returns_the_required_season():void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_get_instance', ['id' => $this->seasonFixtures->testData[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ]
        );
        $document = $this->assertJsonApiGetResponse(Response::HTTP_OK, SeasonResource::TYPE);
        $this->assertEquals($this->seasonFixtures->testData[0]->getId()->toString(), $document->data->id);
        $this->assertEquals($this->seasonFixtures->testData[0]->getName(), $document->data->attributes->name);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_season_does_not_exist():void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_get_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_fails_when_ask_for_wrong_id():void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_get_instance', ['id' => 'wrong-id']),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized():void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_get_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
