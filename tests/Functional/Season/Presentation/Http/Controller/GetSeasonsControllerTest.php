<?php

namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Tests\DataFixtures\Season\SeasonFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetSeasonsControllerTest extends AppApiTestCase
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
    public function it_gets_a_list_of_seasons(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_list'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
        $this->assertArrayHasKey('items', $responseData);
        $this->assertIsArray($responseData['items']);
        $this->assertSameSize($this->seasonFixtures->testData, $responseData['items']);

        for ($i = 0; $i < count($responseData['items']); $i++)
        {
            $this->assertArrayHasKey('id', $responseData['items'][$i]);
            $this->assertArrayHasKey('name', $responseData['items'][$i]);
            $this->assertEquals($this->seasonFixtures->testData[$i]->getId()->toString(), $responseData['items'][$i]['id']);
            $this->assertEquals($this->seasonFixtures->testData[$i]->getName(), $responseData['items'][$i]['name']);
        }
    }

    #[Test]
    public function it_fails_if_not_authorized(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('seasons_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
