<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

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

    #[Test]
    public function testCreateIngredientType()
    {
        $ingredientType = [
            'name' => 'test ingredient type',
        ];
        $this->client->request('POST', $this->getDefinedUrl('create_ingredient_type'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($ingredientType)
        );
        $ingredientTypeDto = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertArrayHasKey('id', $ingredientTypeDto);
        $this->assertNotEmpty($ingredientTypeDto['id']);
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $this->assertResponseHeaderSame('Cache-Control', 'must-revalidate, no-cache, no-store, private');
        $expectedGetUrl = $this->getDefinedUrl('get_ingredient_type', ['id' => $ingredientTypeDto['id']]);
        $this->assertResponseHeaderSame('Location', $expectedGetUrl);
    }

    #[Test]
    public function testCreateIngredientTypeFailsWhenNameIsEmpty(): void
    {
        $invalidPayload = [
            'name' => '',
        ];

        $this->client->request(
            'POST',
            $this->getDefinedUrl('create_ingredient_type'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode($invalidPayload)
        );

        $responseData = $this->assertProblemDetailsResponse(Response::HTTP_BAD_REQUEST);
        $this->assertStringNotContainsString($responseData['title'], 'name: This value should not be blank.');
    }

    #[Test]
    public function testCreateIngredientTypeFailsUnauthenticated(): void
    {
        $this->client->request(
            'POST',
            $this->getDefinedUrl('create_ingredient_type'),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode(['name' => 'test'])
        );

        $responseData = $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals('Missing JWT Token', $responseData['title'] ?? '');
        $this->assertEquals('JWT Token not found', $responseData['detail'] ?? '');
    }

    #[Test]
    public function testCreateIngredientTypeFailsWithExpiredToken(): void
    {
        $expiredToken = $this->createExpiredToken(LoginFixtures::USER_NAME);
        $this->client->request(
            'POST',
            $this->getDefinedUrl('create_ingredient_type'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $expiredToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json'
            ],
            json_encode(['name' => 'test'])
        );

        $responseData = $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals('Expired JWT Token', $responseData['title'] ?? '');
        $this->assertEmpty( $responseData['detail'] ?? '');
    }
}
