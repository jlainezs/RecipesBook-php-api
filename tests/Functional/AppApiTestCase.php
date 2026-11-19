<?php
namespace App\Tests\Functional;

use App\Security\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\ValueObjects\Email;
use App\Tests\DataFixtures\Security\AdditionalUsersFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Throwable;

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

    protected const string CONTENT_TYPE_HEADER_JSON_API = 'application/vnd.api+json';
    protected const string ACCEPT_HEADER_JSON_API = 'application/vnd.api+json';

    public function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $this->container = self::getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();
        $this->userRepository = $this->container->get(UserRepositoryInterface::class);
        $this->executor = new ORMExecutor($this->entityManager, new ORMPurger());

        try
        {
            $this->executor->purge();
        }
        catch (Throwable $t)
        {
            echo "WARN: " . $t->getMessage();
        }

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

    /**
     * Validate that response is a JSON:API error document
     */
    protected function assertJsonApiErrorResponse(int $expectedStatusCode, ?array $expectedSource = null): array
    {
        $this->assertResponseStatusCodeSame($expectedStatusCode);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $content = $this->client->getResponse()->getContent();
        $this->assertJson($content, 'Invalid JSON response.');

        $document = json_decode($content, true);
        $this->assertArrayNotHasKey('data', $document, 'JSON:API: "data" and "errors" must not coexist.');
        $this->assertArrayHasKey('errors', $document, 'JSON:API: missing "errors" key.');
        $this->assertNotEmpty($document['errors'], 'JSON:API: empty "errors".');

        foreach ($document['errors'] as $error) {
            $this->assertArrayHasKey('status', $error, 'JSON:API: missing error "status".');
            $this->assertSame((string) $expectedStatusCode, $error['status'], 'JSON:API: error "status" must be the HTTP status code as a string.');
            $this->assertArrayHasKey('title', $error, 'JSON:API: missing error "title".');
        }

        if (null !== $expectedSource) {
            $this->assertContains($expectedSource, array_column($document['errors'], 'source'));
        }

        return $document;
    }

    private function assertJsonApiResponse(int $expectedStatusCode, string $version = '1.1'): stdClass
    {
        $this->assertResponseStatusCodeSame($expectedStatusCode);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $document = json_decode($this->client->getResponse()->getContent());
        $this->assertIsObject($document);
        $this->assertObjectHasProperty('jsonapi', $document, 'JSON:API: "jsonapi" is required here.');
        $this->assertObjectHasProperty('version', $document->jsonapi, 'JSON:API: "version" is required here.');
        $this->assertEquals($version, $document->jsonapi->version);
        $this->assertObjectHasProperty('links', $document, 'JSON:API: "links" is required here.');
        $this->assertObjectHasProperty('self', $document->links, 'JSON:API: "self" is required here.');
        $this->assertObjectHasProperty('data', $document, 'JSON:API: "data" is required here.');
        $this->assertObjectNotHasProperty('errors', $document, 'JSON:API: "errors" is not allowed here.');

        return $document;
    }

    protected function assertJsonApiCreateResponse(int $expectedStatusCode, string $type, string $version = '1.1'): stdClass
    {
        $this->assertResponseHasHeader('Location');
        $this->assertResponseHeaderSame('Cache-Control','must-revalidate, no-cache, no-store, private');
        $document = $this->assertJsonApiResponse($expectedStatusCode, $version);
        $this->assertObjectHasProperty('id', $document->data);
        $this->assertIsString($document->data->id);
        $this->assertNotEmpty($document->data->id);
        $this->assertObjectHasProperty('type', $document->data);
        $this->assertEquals($type, $document->data->type);
        $this->assertObjectHasProperty('links', $document->data);
        $this->assertObjectHasProperty('self', $document->data->links);
        $this->assertObjectHasProperty('attributes', $document->data);
        $this->assertObjectHasProperty('id', $document->data->attributes);

        return $document;
    }

    private function assertJsonApiItem(stdClass $document, string $type): void
    {
        $this->assertSame($type, $document->type);
        $this->assertObjectHasProperty('id', $document);
        $this->assertIsString($document->id);
        $this->assertNotEmpty($document->id);
        $this->assertObjectHasProperty('attributes', $document);
        $this->assertObjectHasProperty('links', $document);
        $this->assertObjectHasProperty('self', $document->links);
    }

    protected function assertJsonApiGetResponse(int $expectedStatusCode, string $type, string $version = '1.1'): stdClass
    {
        $document = $this->assertJsonApiResponse($expectedStatusCode, $version);
        $this->assertJsonApiItem($document->data, $type);

        return $document;
    }

    protected function assertJsonApiGetCollectionResponse(int $expectedStatusCode, string $type, array $links, string $version = '1.1'): stdClass
    {
        $document = $this->assertJsonApiResponse($expectedStatusCode, $version);
        $this->assertObjectHasProperty('self', $document->links);
        $this->assertEquals($links['self'], $document->links->self);
        $this->assertObjectHasProperty('first', $document->links);
        $this->assertEquals($links['first'], $document->links->first);
        $this->assertObjectHasProperty('prev', $document->links);
        $this->assertEquals($links['prev'], $document->links->prev);
        $this->assertObjectHasProperty('next', $document->links);
        $this->assertEquals($links['next'], $document->links->next);
        $this->assertIsArray($document->data);

        foreach ($document->data as $item)
        {
            $this->assertJsonApiItem($item, $type);
        }

        return $document;
    }
}
