<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Presentation\Http\Resource\IngredientResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateIngredientControllerTest extends  AppApiTestCase
{
    private IngredientTypeFixtures $fixture;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixture = new IngredientTypeFixtures();
        $this->loadFixture($this->fixture);
    }

    private function postDocument(array $document, bool $sendAuth = true): void
    {
        $contentType = 'application/vnd.api+json';
        $accept = 'application/vnd.api+json';

        $headers = [
            'CONTENT_TYPE' => $contentType,
            'HTTP_ACCEPT' => $accept
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'POST',
            $this->getDefinedUrl('ingredient_create'),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    #[Test]
    public function it_creates_ingredient()
    {
        $this->assertNotEmpty($this->fixture->testData);
        $ingredientToCreateData = [
            'data' => [
                'type' => IngredientResource::TYPE,
                'attributes' => [
                    'name' =>'an ingredient',
                    'ingredientTypeId' => $this->fixture->testData[0]->getId()->toString(),
                    'description' =>'no description available'
                ]
            ]
        ];
        $this->postDocument($ingredientToCreateData);
        $this->assertJsonApiCreateResponse(Response::HTTP_CREATED, IngredientResource::TYPE);
    }

    #[Test]
    #[DataProvider('invalidNames')]
    public function it_fails_when_invalid_ingredient_name(string $invalidName): void
    {
        $ingredientToCreateData = [
            'name' => $invalidName,
            'ingredientTypeId' => $this->fixture->testData[0]->getId()->toString(),
            'description' =>'no description available'
        ];
        $this->postDocument($ingredientToCreateData);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    #[DataProvider('invalidUuids')]
    public function it_fails_when_invalid_ingredient_type(string $invalidUuid): void
    {
        $ingredientToCreateData = [
            'name' => 'test',
            'ingredientTypeId' => $invalidUuid,
            'description' =>'no description available'
        ];
        $this->postDocument($ingredientToCreateData);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    public function it_fails_when_no_authorized(): void
    {
        $ingredientToCreateData = [
            'name' => 'test',
            'ingredientTypeId' => $this->fixture->testData[0]->getId()->toString(),
            'description' =>'no description available'
        ];
        $this->postDocument($ingredientToCreateData, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }

    public static function invalidNames(): iterable
    {
        yield 'empty' => [''];
        yield 'spaces' => ['   '];
    }

    public static function invalidUuids(): iterable
    {
        yield 'empty' => [''];
        yield 'spaces' => ['   '];
        yield 'invalid' => ['123abcde-0000-0000-0000-000000000000'];
    }
}
