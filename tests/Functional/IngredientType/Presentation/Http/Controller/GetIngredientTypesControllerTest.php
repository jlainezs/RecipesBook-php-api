<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientTypesControllerTest extends AppApiTestCase
{
    private IngredientTypeFixtures $fixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new IngredientTypeFixtures();
        $this->loadFixture($this->fixtures);
    }

    #[Test]
    public function it_gets_the_ingredients_types(): void
    {
        $this->client->request(
            'GET', $this->getDefinedUrl('ingredient_types_list'),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ],
        );
        $document = $this->assertJsonApiGetCollectionResponse(
            Response::HTTP_OK,
            IngredientTypeResource::TYPE,
            [
                'self' => $this->getDefinedUrl('ingredient_types_list', ['page' => ['offset' => 0, 'limit' => 20]]),
                'first' => $this->getDefinedUrl('ingredient_types_list', ['page' => ['offset' => 0, 'limit' => 20]]),
                'prev' => null,
                'next' => null,
            ]
        );

        for ($i = 0; $i < count($document->data); $i++)
        {
            $this->assertSame(IngredientTypeResource::TYPE, $document->data[$i]->type);
            $this->assertObjectHasProperty('id', $document->data[$i]);
            $this->assertObjectHasProperty('name', $document->data[$i]->attributes);
            $this->assertEquals($this->fixtures->testData[$i]->getId()->toString(), $document->data[$i]->id);
            $this->assertEquals($this->fixtures->testData[$i]->getName()->value(), $document->data[$i]->attributes->name);
        }
    }

    #[Test]
    public function it_fails_when_not_authorized(): void
    {
        $this->client->request(
            'GET', $this->getDefinedUrl('ingredient_types_list'),
            [],
            [],
            [
                'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
            ],
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
