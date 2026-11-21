<?php
namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
use App\UnitOfMeasure\Presentation\Http\Resource\UnitOfMeasureResource;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetUnitOfMeasureControllerTest extends AppApiTestCase
{
    public UnitOfMeasureFixture $unitOfMeasureFixture;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->unitOfMeasureFixture = new UnitOfMeasureFixture();
        $this->loadFixture($this->unitOfMeasureFixture);
    }

    public function getDocument(mixed $id, bool $sendAuth = true, array $additionalQueryParams = [], string $altAcceptHeader = ''): void
    {
        $headers = [
            'HTTP_ACCEPT' => empty($altAcceptHeader) ? self::ACCEPT_HEADER_JSON_API : $altAcceptHeader
        ];

        $queryParams = array_merge($additionalQueryParams, ['id' => $id]);

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_get_instance', $queryParams),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function it_should_return_the_required_unit_of_measure(): void
    {
        $this->getDocument($this->unitOfMeasureFixture->testData[0]->getId()->toString());
        $document = $this->assertJsonApiGetResponse(Response::HTTP_OK, UnitOfMeasureResource::TYPE);
        $this->assertEquals($this->unitOfMeasureFixture->testData[0]->getId()->toString(), $document->data->id);
        $this->assertSame($this->unitOfMeasureFixture->testData[0]->getName(), $document->data->attributes->name);
        $this->assertObjectHasProperty('symbol', $document->data->attributes);
        $this->assertObjectHasProperty('unitOfMeasureType', $document->data->attributes);
        $this->assertObjectHasProperty('createdAt', $document->data->attributes);
        $this->assertObjectHasProperty('updatedAt', $document->data->attributes);
        $this->assertObjectHasProperty('self', $document->links);
    }

    #[Test]
    public function it_fails_when_accept_has_only_unsupported_media_type_parameters(): void
    {
        $this->getDocument($this->unitOfMeasureFixture->testData[0]->getId(), true, [], self::ACCEPT_HEADER_JSON_API . '; charset=utf-8');
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_ACCEPTABLE, ['header' => 'Accept']);
    }

    #[Test]
    public function it_fails_with_unsupported_include_parameter(): void
    {
        $this->getDocument($this->unitOfMeasureFixture->testData[0]->getId(), true, ['include' => 'ingredients']);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST, ['parameter' => 'include']);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_unit_of_measure_does_not_exists(): void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
