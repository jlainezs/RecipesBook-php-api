<?php
namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetUnitsOfMeasureControllerTest extends AppApiTestCase
{
    public UnitOfMeasureFixture $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new UnitOfMeasureFixture();
        $this->loadFixture($this->fixtures);
    }

    #[Test]
    public function it_returns_a_list_of_units_of_measure(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_list'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);
        foreach ($response->data as $resource) {
            $this->assertSame('units-of-measure', $resource->type);
            $this->assertObjectHasProperty('id', $resource);
            $this->assertObjectHasProperty('attributes', $resource);
        }
        $this->assertSame(
            $this->getDefinedUrl('unit_of_measure_list', ['page' => ['offset' => 0, 'limit' => 20]]),
            $response->links->first
        );
        $this->assertNull($response->links->prev);
        $this->assertNull($response->links->next);
    }

    #[Test]
    public function it_paginates_with_page_parameters(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_list', ['page' => ['offset' => 1, 'limit' => 1]]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(1, $response->data);
        $this->assertSame(
            $this->getDefinedUrl('unit_of_measure_list', ['page' => ['offset' => 0, 'limit' => 1]]),
            $response->links->prev
        );
        $this->assertSame(
            $this->getDefinedUrl('unit_of_measure_list', ['page' => ['offset' => 2, 'limit' => 1]]),
            $response->links->next
        );
    }

    #[Test]
    public function it_fails_with_invalid_page_parameters(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_list', ['page' => ['limit' => 500]]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST, ['parameter' => 'page[limit]']);
    }

    #[Test]
    public function it_fails_with_non_json_api_query_parameters(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_list', ['offset' => 0, 'limit' => 10]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST, ['parameter' => 'offset']);
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/vnd.api+json'
            ]
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
