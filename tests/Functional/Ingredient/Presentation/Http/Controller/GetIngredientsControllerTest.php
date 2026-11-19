<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Presentation\Http\Resource\IngredientResource;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientsControllerTest extends AppApiTestCase
{
    private IngredientFixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new IngredientFixtures();
        $this->loadFixture($this->fixtures);
    }

    private function getDocument(int $offset = 0, int $limit = 10, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
        ];
        $params = [
            'page' => [
                'offset' => $offset,
                'limit' => $limit,
            ],
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_list', $params),
            [],
            [],
            $headers,
        );
    }

    #[Test]
    public function it_returns_a_list_of_ingredients(): void
    {
        $this->getDocument();
        $document = $this->assertJsonApiGetCollectionResponse(
            Response::HTTP_OK,
            IngredientResource::TYPE,
            [
                'self' => $this->getDefinedUrl('ingredient_list', ['page' => ['offset' => 0, 'limit' => 10]]),
                'first' => $this->getDefinedUrl('ingredient_list', ['page' => ['offset' => 0, 'limit' => 10]]),
                'prev' => null,
                'next' => null,
            ]
        );
        $this->assertSameSize($this->fixtures->testData, $document->data);

        foreach($document->data as $resource)
        {
            $this->assertObjectHasProperty('name', $resource->attributes);
            $this->assertObjectHasProperty('description', $resource->attributes);
        }
    }

    #[Test]
    public function getIngredientsFailsWhenNoToken(): void
    {
        $this->getDocument(0, 10, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
