<?php

namespace App\Tests\Functional\Season\Presentation\Http\Controller;

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
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertIsObject($response->season);
        $this->assertEquals($this->seasonFixtures->testData[0]->getId()->toString(), $response->season->id);
        $this->assertEquals($this->seasonFixtures->testData[0]->getName(), $response->season->name);
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
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
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
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
