<?php

namespace App\Tests\Functional\Shared\Presentation\Http;

use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HealthCheckControllerTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    private function getDocument(bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => 'application/vnd.api+json'
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('api_v1_healthcheck'),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function it_should_respond_ok(): void
    {
        $this->getDocument();
        $this->assertResponseIsSuccessful();
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $this->getDocument(false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
