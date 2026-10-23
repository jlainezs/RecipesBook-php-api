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
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('items', $response);
        $this->assertSameSize($this->fixtures->testData, $response->items);
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
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
