<?php
namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\DataFixtures\UnitOfMeasure\UnitOfMeasureFixture;
use App\Tests\Functional\AppApiTestCase;
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

    #[Test]
    public function it_should_return_the_required_unit_of_measure(): void
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_get_instance', ['id' => $this->unitOfMeasureFixture->testData[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('unitOfMeasure', $response);
        $this->assertIsObject($response->unitOfMeasure);
        $this->assertObjectHasProperty('id', $response->unitOfMeasure);
        $this->assertEquals($this->unitOfMeasureFixture->testData[0]->getId()->toString(), $response->unitOfMeasure->id);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_unit_of_measure_does_not_exists(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'GET',
            $this->getDefinedUrl('unit_of_measure_get_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
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
            'GET',
            $this->getDefinedUrl('unit_of_measure_get_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
