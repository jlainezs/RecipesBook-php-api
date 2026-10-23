<?php

namespace App\Tests\Functional\UnitOfMeasure\Presentation\Http\Controller;

use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasureEnum;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UnitOfMeasureControllerTest extends AppApiTestCase
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
        $unit = [
            'name' => 'test',
            'symbol' => 't',
            'unitOfMeasureType' => UnitOfMeasureEnum::Units
        ];
        $this->client->request(
            'POST',
            $this->getDefinedUrl('unit_of_measure_create'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($unit)
        );
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($response);
        $this->assertIsObject($response);
        $this->assertObjectHasProperty('id', $response);
        $this->assertIsString($response->id);
        $this->assertNotEmpty($response->id);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('unit_of_measure_get_instance', ['id' => $response->id]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
    }
}
