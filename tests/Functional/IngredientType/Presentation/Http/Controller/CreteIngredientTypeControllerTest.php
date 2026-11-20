<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreteIngredientTypeControllerTest extends AppApiTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
    }

    private function postDocument(array $document, bool $sendAuth = true, bool $expiredToken = false): void
    {
        $headers = [
            'CONTENT_TYPE' => self::CONTENT_TYPE_HEADER_JSON_API,
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' .
                ($expiredToken
                    ? $this->createExpiredToken(LoginFixtures::USER_NAME)
                    : $this->userToken);
        }

        $this->client->request(
            'POST',
            $this->getDefinedUrl('create_ingredient_type'),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    #[Test]
    public function testCreateIngredientType()
    {
        $ingredientType = [
            'data' => [
                'type' => IngredientTypeResource::TYPE,
                'attributes' => [
                    'name' => 'test ingredient type',
                ]
            ]
        ];
        $this->postDocument($ingredientType);
        $document = $this->assertJsonApiCreateResponse(Response::HTTP_CREATED, IngredientTypeResource::TYPE);
    }

    #[Test]
    public function testCreateIngredientTypeFailsWhenNameIsEmpty(): void
    {
        $invalidPayload = [
            'data' => [
                'type' => IngredientTypeResource::TYPE,
                'attributes' => [
                    'name' => '',
                ]
            ]
        ];
        $this->postDocument($invalidPayload);
        $this->assertJsonApiErrorResponse(Response::HTTP_BAD_REQUEST);
    }

    #[Test]
    public function testCreateIngredientTypeFailsUnauthenticated(): void
    {
        $ingredientType = [
            'data' => [
                'type' => IngredientTypeResource::TYPE,
                'attributes' => [
                    'name' => 'test ingredient type',
                ]
            ]
        ];
        $this->postDocument($ingredientType, false);

        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function testCreateIngredientTypeFailsWithExpiredToken(): void
    {
        $ingredientType = [
            'data' => [
                'type' => IngredientTypeResource::TYPE,
                'attributes' => [
                    'name' => 'test ingredient type',
                ]
            ]
        ];
        $this->postDocument($ingredientType, true, true);

        $responseData = $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
