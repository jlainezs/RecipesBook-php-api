<?php
namespace App\Tests\Functional\Season\Presentation\Http\Controller;

use App\Season\Domain\Repository\SeasonRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Season\SeasonFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteSeasonControllerTest extends AppApiTestCase
{
    private SeasonFixtures $seasonFixtures;
    private SeasonRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->seasonFixtures = new SeasonFixtures();
        $this->loadFixture($this->seasonFixtures);
        $this->repository = $this->container->get(SeasonRepositoryInterface::class);
    }

    private function deleteDocument(mixed $id, bool $sendAuth = true): void
    {
        $headers = ['HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('seasons_delete_instance', ['id' => $id]),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function it_should_delete_a_season(): void
    {
        $this->deleteDocument($this->seasonFixtures->testData[0]->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure is deleted
        $season = $this->repository->findOne($this->seasonFixtures->testData[0]->getId());
        $this->assertNull($season);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_season_does_not_exist(): void
    {
        $id = AggregateRootId::generateId();
        $this->deleteDocument($id);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_asking_for_wrong_id(): void
    {
        $id = 'wrong-id';
        $this->deleteDocument($id);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->deleteDocument($id, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
