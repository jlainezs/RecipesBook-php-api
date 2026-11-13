<?php

namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Tests\DataFixtures\Season\SeasonFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetSeasonsControllerTest extends AppApiTestCase
{
    private SeasonFixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new SeasonFixtures();
        $this->loadFixture($this->fixtures);
    }

    public function getDocument(int $offset = 0, int $limit = 10, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];
        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }
        $params = [
            'page' => [
                'offset' => $offset,
                'limit' => $limit,
            ],
        ];
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_list', $params),
            [],
            [],
            $headers,
        );
    }

    #[Test]
    public function it_gets_a_list_of_seasons(): void
    {
        $this->getDocument();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);

        foreach($response->data as $item)
        {
            $this->assertSame(SeasonResource::TYPE, $item->type);
            $this->assertObjectHasProperty('id', $item);
            $this->assertObjectHasProperty('name', $item->attributes);
        }

        $this->assertSame(
            $this->getDefinedUrl('seasons_list', ['page' => ['offset' => 0, 'limit' => 10]]),
            $response->links->first
        );
        $this->assertNull($response->links->prev);
        $this->assertNull($response->links->next);
    }

    #[Test]
    public function it_fails_if_not_authorized(): void
    {
        $this->getDocument(0, 10, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
