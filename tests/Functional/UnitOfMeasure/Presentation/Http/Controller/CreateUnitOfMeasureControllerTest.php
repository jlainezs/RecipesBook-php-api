<?php

namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUnitOfMeasureControllerTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    #[Test]
    public function create_unit_of_measure()
    {
        $this->postDocument([
            'data' => [
                'type' => 'units-of-measure',
                'attributes' => [
                    'name' => 'test',
                    'symbol' => 't',
                    'unitOfMeasureType' => UnitOfMeasureEnum::Units->value
                ]
            ]
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSame('units-of-measure', $response->data->type);
        $this->assertIsString($response->data->id);
        $this->assertNotEmpty($response->data->id);
        $this->assertSame('test', $response->data->attributes->name);
        $this->assertSame('t', $response->data->attributes->symbol);
        $this->assertSame(UnitOfMeasureEnum::Units->value, $response->data->attributes->unitOfMeasureType);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('unit_of_measure_get_instance', ['id' => $response->data->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
        $this->assertSame($expectedGetUrl, $response->data->links->self);
    }

    #[Test]
    public function it_fails_with_invalid_attributes(): void
    {
        $this->postDocument([
            'data' => [
                'type' => 'units-of-measure',
                'attributes' => [
                    'name' => '',
                    'symbol' => 't',
                    'unitOfMeasureType' => 7
                ]
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, ['pointer' => '/data/attributes/name']);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, ['pointer' => '/data/attributes/unitOfMeasureType']);
    }

    #[Test]
    public function it_fails_when_a_required_attribute_is_missing(): void
    {
        $this->postDocument([
            'data' => [
                'type' => 'units-of-measure',
                'attributes' => [
                    'name' => 'test',
                    'unitOfMeasureType' => UnitOfMeasureEnum::Units->value
                ]
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, ['pointer' => '/data/attributes/symbol']);
    }

    #[Test]
    public function it_fails_when_resource_type_does_not_match(): void
    {
        $this->postDocument([
            'data' => [
                'type' => 'ingredients',
                'attributes' => ['name' => 'test', 'symbol' => 't', 'unitOfMeasureType' => 0]
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_CONFLICT, ['pointer' => '/data/type']);
    }

    #[Test]
    public function it_rejects_client_generated_ids(): void
    {
        $this->postDocument([
            'data' => [
                'type' => 'units-of-measure',
                'id' => 'a0cc5259-e742-45ae-8dbc-d875c4354e48',
                'attributes' => ['name' => 'test', 'symbol' => 't', 'unitOfMeasureType' => 0]
            ]
        ]);
        $this->assertJsonApiErrorResponse(Response::HTTP_FORBIDDEN, ['pointer' => '/data/id']);
    }

    #[Test]
    public function it_fails_when_document_has_no_primary_data(): void
    {
        $this->postDocument(['name' => 'test', 'symbol' => 't', 'unitOfMeasureType' => 0]);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST, ['pointer' => '/data']);
    }

    #[Test]
    public function it_fails_with_unsupported_content_type(): void
    {
        $this->postDocument(
            ['data' => ['type' => 'units-of-measure', 'attributes' => ['name' => 'test', 'symbol' => 't', 'unitOfMeasureType' => 0]]],
            'application/json'
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, ['header' => 'Content-Type']);
    }

    #[Test]
    public function it_fails_with_content_type_parameters_other_than_ext_or_profile(): void
    {
        $this->postDocument(
            ['data' => ['type' => 'units-of-measure', 'attributes' => ['name' => 'test', 'symbol' => 't', 'unitOfMeasureType' => 0]]],
            'application/vnd.api+json; charset=utf-8'
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNSUPPORTED_MEDIA_TYPE, ['header' => 'Content-Type']);
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $this->client->request(
            'POST',
            $this->getDefinedUrl('unit_of_measure_create'),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/vnd.api+json',
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ],
            json_encode(['data' => ['type' => 'units-of-measure', 'attributes' => ['name' => 'test', 'symbol' => 't', 'unitOfMeasureType' => 0]]])
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }

    private function postDocument(array $document, string $contentType = 'application/vnd.api+json'): void
    {
        $this->client->request(
            'POST',
            $this->getDefinedUrl('unit_of_measure_create'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => $contentType,
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ],
            json_encode($document)
        );
    }
}
