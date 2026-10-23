<?php

namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Domain\Repository\SeasonRepositoryInterface;
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

    #[Test]
    public function it_updates_a_season(): void
    {
        $toUpdate = $this->seasonRepository->findOne($this->seasonFixtures->testData[0]->getId());
        $this->assertNotNull($toUpdate);

        $this->client->request(
            'PUT',
            $this->getDefinedUrl('seasons_update_instance', ['id' => $toUpdate->getId()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'name' => 'updated',
            ])
        );
        $this->assertResponseIsSuccessful();
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

        $this->client->request(
            'PUT',
            $this->getDefinedUrl('seasons_update_instance', ['id' => $toUpdate->getId()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'name' => $name,
            ])
        );

        $this->assertProblemDetailsResponse(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('seasons_update_instance', ['id' => $id->getId()->toString()]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'name' => 'updated',
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }

    public static function invalidNames(): iterable
    {
        yield 'empty' => [''];
        yield 'spaces' => ['   '];
    }
}
