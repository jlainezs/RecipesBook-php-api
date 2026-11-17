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
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'id' => $id->toString(),
                'attributes' => $newData
            ]
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $updated = $this->repository->findOne($id);
        $this->assertSame($newData['name'], $updated->getName());
    }

    #[Test]
    public function it_keeps_the_attributes_not_sent(): void
    {
        $original = $this->fixture->testData[0];
        $id = $original->getId();
        $originalSymbol = $original->getSymbol()->value();
        $originalType = $original->getUomType();
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'id' => $id->toString(),
                'attributes' => ['name' => 'only the name changes']
            ]
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $unit = $this->repository->findOne($id);
        $this->assertSame('only the name changes', $unit->getName());
        $this->assertSame($originalSymbol, $unit->getSymbol()->value());
        $this->assertSame($originalType, $unit->getUomType());
    }

    #[Test]
    public function it_allows_changing_to_weight(): void
    {
        $id = $this->fixture->testData[0]->getId();
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'id' => $id->toString(),
                'attributes' => ['unitOfMeasureType' => UnitOfMeasureEnum::Weight->value]
            ]
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $unit = $this->repository->findOne($id);
        $this->assertSame(UnitOfMeasureEnum::Weight, $unit->getUomType());
    }

    #[Test]
    public function it_fails_when_id_does_not_match_the_url(): void
    {
        $id = $this->fixture->testData[0]->getId();
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'id' => $this->fixture->testData[1]->getId()->toString(),
                'attributes' => ['name' => 'test name changed']
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_CONFLICT, ['pointer' => '/data/id']);
    }

    #[Test]
    public function it_fails_when_id_is_missing(): void
    {
        $id = $this->fixture->testData[0]->getId();
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'attributes' => ['name' => 'test name changed']
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST, ['pointer' => '/data']);
    }

    #[Test]
    public function it_fails_with_blank_name(): void
    {
        $id = $this->fixture->testData[0]->getId();
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'id' => $id->toString(),
                'attributes' => ['name' => '']
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, ['pointer' => '/data/attributes/name']);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_shopping_list_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $this->patchDocument($id->toString(), [
            'data' => [
                'type' => 'units-of-measure',
                'id' => $id->toString(),
                'attributes' => [
                    'name' => 'test name changed',
                    'symbol' => 't',
                    'unitOfMeasureType' => UnitOfMeasureEnum::Units->value
                ]
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PATCH',
            $this->getDefinedUrl('unit_of_measure_update', ['id' => $id->toString()]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/vnd.api+json',
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ],
            json_encode([
                'data' => [
                    'type' => 'units-of-measure',
                    'id' => $id->toString(),
                    'attributes' => ['name' => 'test name changed']
                ]
            ])
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }

    static public function validData(): iterable
    {
        yield 'change_name' => [
                [
                    'name' => 'test name changed',
                    'symbol' => 't',
                    'unitOfMeasureType' => UnitOfMeasureEnum::Units->value
                ]
            ];
    }

    private function patchDocument(string $id, array $document): void
    {
        $this->client->request(
            'PATCH',
            $this->getDefinedUrl('unit_of_measure_update', ['id' => $id]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/vnd.api+json',
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ],
            json_encode($document)
        );
    }
}
