<?php
namespace App\Tests\Integration\Security\Users;

use App\DataFixtures\LoginFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginTest extends WebTestCase
{
    #[Test]
    public function Login_will_succeed(): void
    {
        // setup client (boots the kernel)
        $client = self::createClient();

        // setup data
        $container = self::getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $fixtureLoader = $container->get('doctrine.fixtures.loader');
        $fixtureLoader->addFixture(new LoginFixtures($container->get(UserPasswordHasherInterface::class)));
        $executor = new ORMExecutor($entityManager, new ORMPurger());
        $executor->execute($fixtureLoader->getFixtures());

        // act
        $client->jsonRequest('POST', '/api/v1/login_check', [
            'username' => 'test@example.com',
            'password' => 'test',
        ]);

        // asserts
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertNotEmpty($responseData['token']);
    }
}
