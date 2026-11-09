<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\Ingredient\Presentation\Http\Resoource\IngredientResource;
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
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);
        $this->assertSameSize($this->fixtures->testData, $response->data);

        for ($i = 0; $i < count($response->data); $i++)
        {
            $this->assertSame(IngredientTypeResource::TYPE, $response->data[$i]->type);
            $this->assertObjectHasProperty('id', $response->data[$i]);
            $this->assertObjectHasProperty('name', $response->data[$i]->attributes);
            $this->assertEquals($this->fixtures->testData[$i]->getId()->toString(), $response->data[$i]->id);
            $this->assertEquals($this->fixtures->testData[$i]->getName()->value(), $response->data[$i]->attributes->name);
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
                'HTTP_ACCEPT' => 'application/json'
            ],
        );
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
