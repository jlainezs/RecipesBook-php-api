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
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
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
        $document = $this->assertJsonApiGetCollectionResponse(
            Response::HTTP_OK,
            SeasonResource::TYPE,
            [
                'self' => $this->getDefinedUrl('seasons_list', ['page' => ['offset' => 0, 'limit' => 10]]),
                'first' => $this->getDefinedUrl('seasons_list', ['page' => ['offset' => 0, 'limit' => 10]]),
                'prev' => null,
                'next' => null,
            ]
        );
        $this->assertNotEmpty($document);
        $this->assertSameSize($this->fixtures->testData, $document->data);

        foreach($document->data as $item)
        {
            $this->assertSame(SeasonResource::TYPE, $item->type);
            $this->assertObjectHasProperty('id', $item);
            $this->assertObjectHasProperty('name', $item->attributes);
        }
    }

    #[Test]
    public function it_fails_if_not_authorized(): void
    {
        $this->getDocument(0, 10, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
