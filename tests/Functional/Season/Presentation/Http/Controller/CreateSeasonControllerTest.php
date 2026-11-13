<?php
namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateSeasonControllerTest extends AppApiTestCase
{
    private SeasonRepositoryInterface $seasonRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->seasonRepository = $this->container->get(SeasonRepositoryInterface::class);
    }

    private function postDocument(array $document, bool $sendAuth = true): void
    {
        $contentType = 'application/vnd.api+json';
        $accept = 'application/vnd.api+json';

        $headers = [
            'CONTENT_TYPE' => $contentType,
            'HTTP_ACCEPT' => $accept
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'POST',
            $this->getDefinedUrl('seasons_create'),
            [],[],
            $headers,
            json_encode($document)
        );
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_a_season(): void
    {
        $rawData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'attributes' => [
                    'name' => 'Season 1',
                ],
            ],
        ];
        $this->postDocument($rawData);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent());

        $this->assertIsObject($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertObjectHasProperty('id', $response->data);
        $this->assertIsString($response->data->id);
        $this->assertNotEmpty($response->data->id);
        $this->assertObjectHasProperty('type', $response->data);
        $expectedGetUrl = $this->getDefinedUrl('seasons_get_instance', ['id' => $response->data->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $rawData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'attributes' => [
                    'name' => 'Season 1',
                ],
            ],
        ];
        $this->postDocument($rawData, false);

        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }

    #[Test]
    #[DataProvider('invalidNames')]
    public function it_fails_when_invalid_name(string $name): void
    {
        $rawData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'attributes' => [
                    'name' => $name,
                ],
            ],
        ];
        $this->postDocument($rawData);

        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    public static function invalidNames(): iterable
    {
        yield 'empty name' => [''];
        yield 'spaces' => ['   '];
    }
}
