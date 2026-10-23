<?php
namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUnitOfMeasureControllerTest extends AppApiTestCase
{
    public UnitOfMeasureFixture $fixture;
    public UnitOfMeasureRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixture = new UnitOfMeasureFixture();
        $this->loadFixture($this->fixture);
        $this->repository = $this->container->get(UnitOfMeasureRepositoryInterface::class);
    }

    #[Test]
    #[DataProvider('validData')]
    public function it_updates_a_unit_of_measure(array $newData): void
    {
        $id = $this->fixture->testData[0]->getId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('unit_of_measure_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($newData)
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $unit = $this->repository->findOne($id);
        $this->assertEquals($newData['name'], $unit->getName());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_shopping_list_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('unit_of_measure_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode([
                'name' => 'test name changed',
                'symbol' => 't',
                'unitOfMeasureType' => UnitOfMeasureEnum::Units
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
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
            $this->getDefinedUrl('unit_of_measure_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode([
                'name' => 'test name changed',
                'symbol' => 't',
                'unitOfMeasureType' => UnitOfMeasureEnum::Units
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }

    static public function validData(): iterable
    {
        yield 'change_name' => [
                [
                    'name' => 'test name changed',
                    'symbol' => 't',
                    'unitOfMeasureType' => UnitOfMeasureEnum::Units
                ]
            ];
    }
}
