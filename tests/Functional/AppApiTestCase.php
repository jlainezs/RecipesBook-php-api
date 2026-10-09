<?php
namespace App\Tests\Functional;

use App\Shared\Domain\ValueObjects\Email;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\DataFixtures\Security\AdditionalUsersFixtures;
use App\Security\Domain\Repository\UserRepositoryInterface;
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
    protected UrlGeneratorInterface $urlGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $this->executor->purge();
        $this->urlGenerator = $this->container->get(UrlGeneratorInterface::class);
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

    protected function createExpiredToken(string $email, int $expirationTime = 3600): string
    {
        $jwtManager = $this->container->get(JWTTokenManagerInterface::class);
        $user = $this->userRepository->findOneByEmail(new Email($email));

        $payload = [
            'exp' => time() - $expirationTime,
        ];

        return $jwtManager->createFromPayload($user, $payload);
    }

    protected function getDefinedUrl(string $routeName, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_URL): string
    {
        return $this->urlGenerator->generate(
            $routeName,
            $parameters,
            $referenceType
        );
    }

    protected function loadFixture(FixtureInterface $fixture): void
    {
        $this->executor->execute([$fixture], true);
    }

    /**
     * Validate that response follows RFC 9457 (Problem Details)
     */
    protected function assertProblemDetailsResponse(
        int $expectedStatusCode,
        ?string $expectedTitle = null,
        ?string $expectedType = null
    ): array {
        $this->assertResponseStatusCodeSame($expectedStatusCode);
        $this->assertResponseHeaderSame('Content-Type', 'application/problem+json');
        $content = $this->client->getResponse()->getContent();
        $this->assertJson($content, 'Invalid JSON response.');

        $data = json_decode($content, true);
        $this->assertArrayHasKey('status', $data, 'RFC 9457: missing "status" key.');
        $this->assertEquals($expectedStatusCode, $data['status'], 'RFC 9457: Unexpected status received. Received ' . $data['status'] . '. Expected: ' . $expectedStatusCode . '.');

        $this->assertArrayHasKey('title', $data, 'RFC 9457: missing "title" key.');
        $this->assertNotEmpty($data['title'], 'RFC 9457: empty "title".');

        $this->assertArrayHasKey('type', $data, 'RFC 9457: missing "type"key.');
        $this->assertArrayHasKey('detail', $data, 'RFC 9457: missing "detail" key.');

        if (null !== $expectedTitle) {
            $this->assertEquals($expectedTitle, $data['title']);
        }

        if (null !== $expectedType) {
            $this->assertEquals($expectedType, $data['type']);
        }

        return $data;
    }
}
