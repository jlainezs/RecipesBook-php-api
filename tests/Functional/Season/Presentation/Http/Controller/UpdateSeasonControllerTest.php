<?php
namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Season\Presentation\Http\Resource\SeasonResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Season\SeasonFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateSeasonControllerTest extends AppApiTestCase
{
    private SeasonFixtures $seasonFixtures;
    private SeasonRepositoryInterface $seasonRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->seasonFixtures = new SeasonFixtures();
        $this->loadFixture($this->seasonFixtures);
        $this->seasonRepository = $this->container->get(SeasonRepositoryInterface::class);
    }

    private function patchDocument(mixed $id, array $document, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => self::CONTENT_TYPE_HEADER_JSON_API,
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API,
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'PATCH',
            $this->getDefinedUrl('seasons_update_instance', ['id' => $id]),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    #[Test]
    public function it_updates_a_season(): void
    {
        $toUpdate = $this->seasonRepository->findOne($this->seasonFixtures->testData[0]->getId());
        $this->assertNotNull($toUpdate);
        $updatedData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'id' => $toUpdate->getId()->toString(),
                'attributes' => [
                    'name' => 'updated',
                ]
            ]
        ];

        $this->patchDocument($toUpdate->getId(), $updatedData);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $updated = $this->seasonRepository->findOne($toUpdate->getId());
        $this->assertNotNull($updated);
        $this->assertEquals('updated', $updated->getName());
    }

    #[Test]
    #[DataProvider('invalidNames')]
    public function it_fails_when_using_invalid_name(string $name): void
    {
        $toUpdate = $this->seasonRepository->findOne($this->seasonFixtures->testData[0]->getId());
        $this->assertNotNull($toUpdate);

        $updatedData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'id' => $toUpdate->getId()->toString(),
                'attributes' => [
                    'name' => $name,
                ]
            ]
        ];

        $this->patchDocument($toUpdate->getId(), $updatedData);

        $this->assertJsonApiErrorResponse(
                empty($name)
                    ? Response::HTTP_UNPROCESSABLE_ENTITY
                    : Response::HTTP_BAD_REQUEST
        );
    }

    #[Test]
    public function it_fails_when_using_invalid_id(): void
    {
        $wrongId = 'wrong-id';
        $updatedData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'id' => $wrongId,
                'attributes' => [
                    'name' => "a season",
                ]
            ]
        ];

        $this->patchDocument($wrongId, $updatedData);

        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $toUpdate = $this->seasonRepository->findOne($this->seasonFixtures->testData[0]->getId());
        $this->assertNotNull($toUpdate);

        $updatedData = [
            'data' => [
                'type' => SeasonResource::TYPE,
                'id' => $toUpdate->getId()->toString(),
                'attributes' => [
                    'name' => 'someName',
                ]
            ]
        ];

        $this->patchDocument($toUpdate->getId(), $updatedData, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }

    public static function invalidNames(): iterable
    {
        yield 'empty' => [''];
        yield 'spaces' => ['   '];
    }
}
