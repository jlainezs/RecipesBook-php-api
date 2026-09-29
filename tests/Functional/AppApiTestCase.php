<?php
namespace App\Tests\Functional;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

abstract class AppApiTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected Container $container;
    protected SymfonyFixturesLoader $fixtureLoader;
    protected ObjectManager $entityManager;
    protected ORMExecutor $executor;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->fixtureLoader = $this->container->get('doctrine.fixtures.loader');
        $this->executor = new ORMExecutor($this->entityManager, new ORMPurger());
    }

    protected function loginWithUser(string $userName, string $password): void
    {
        $this->client->jsonRequest('POST', '/api/v1/login_check', [
            'username' => $userName,
            'password' => $password,
        ]);
    }

    protected function getAuthToken(): string
    {
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        return $responseData['token'] ?? '';
    }

    protected function loadFixture(FixtureInterface $fixture): void
    {
        $this->fixtureLoader->addFixture($fixture);
        $this->executor->execute($this->fixtureLoader->getFixtures());

    }
}
