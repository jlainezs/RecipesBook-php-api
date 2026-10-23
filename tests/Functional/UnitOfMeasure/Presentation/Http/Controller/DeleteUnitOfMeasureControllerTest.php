<?php
namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteUnitOfMeasureControllerTest extends AppApiTestCase
{
    public UnitOfMeasureFixture $unitOfMeasureFixture;
    public UnitOfMeasureRepositoryInterface $unitOfMeasureRepository;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->unitOfMeasureFixture = new UnitOfMeasureFixture();
        $this->loadFixture($this->unitOfMeasureFixture);
        $this->unitOfMeasureRepository = $this->container->get(UnitOfMeasureRepositoryInterface::class);
    }

    #[Test]
    public function it_should_delete_an_unit_of_measure(): void
    {
        $toDelete = $this->unitOfMeasureFixture->testData[0]->getId();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('unit_of_measure_delete', ['id' => $toDelete->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        //ensure deleted
        $unit = $this->unitOfMeasureRepository->findOne($toDelete);
        $this->assertNull($unit);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_unit_of_measure_does_not_exist(): void
    {
        $toDelete = AggregateRootId::generateId();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('unit_of_measure_delete', ['id' => $toDelete->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $toDelete = AggregateRootId::generateId();
        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('unit_of_measure_delete', ['id' => $toDelete->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
