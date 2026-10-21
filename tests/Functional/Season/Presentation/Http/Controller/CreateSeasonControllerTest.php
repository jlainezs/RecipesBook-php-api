<?php
namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Domain\Repository\SeasonRepositoryInterface;
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

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_creates_a_season(): void
    {
        $rawData = [
            'name' => 'Season 1',
        ];
        $this->client->request(
            'POST',
            $this->getDefinedUrl('seasons_create'),
            [],[],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($rawData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertObjectHasProperty('id', $response);
        $this->assertNotEmpty($response->id);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('seasons_get_instance', ['id' => $response->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $rawData = [
            'name' => 'Season 1',
        ];
        $this->client->request(
            'POST',
            $this->getDefinedUrl('seasons_create'),
            [],[],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($rawData)
        );

        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }

    #[Test]
    #[DataProvider('invalidNames')]
    public function it_fails_when_invalid_name(string $name): void
    {
        $rawData = [
            'name' => $name,
        ];
        $this->client->request(
            'POST',
            $this->getDefinedUrl('seasons_create'),
            [],[],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode($rawData)
        );

        $this->assertProblemDetailsResponse(Response::HTTP_BAD_REQUEST);
    }

    public static function invalidNames(): iterable
    {
        yield 'empty name' => [''];
        yield 'spaces' => ['   '];
    }
}
