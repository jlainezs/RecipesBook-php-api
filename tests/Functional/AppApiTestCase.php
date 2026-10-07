<?php
namespace App\Tests\Functional;

use App\DataFixtures\Security\AdditionalUsersFixtures;
use App\Security\Domain\Repository\UserRepositoryInterface;
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
    protected ObjectManager $entityManager;
    protected ORMExecutor $executor;
    protected string $userToken;
    protected UserRepositoryInterface $userRepository;
    protected AdditionalUsersFixtures $additionalUsersFixtures;


    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $this->executor->purge();
    }

    protected function loginWithUser(string $userName, string $password): void
    {
        $this->client->jsonRequest('POST', '/api/v1/login_check', [
            'username' => $userName,
            'password' => $password,
        ]);
        $this->userToken = $this->getAuthToken();
    }

    protected function getAuthToken(): string
    {
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        return $responseData['token'] ?? '';
    }

    protected function loadFixture(FixtureInterface $fixture): void
    {
        $this->executor->execute([$fixture], true);
    }
}
