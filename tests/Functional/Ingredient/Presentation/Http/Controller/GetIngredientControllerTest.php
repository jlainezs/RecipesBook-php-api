<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Presentation\Http\Resource\IngredientResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientControllerTest extends AppApiTestCase
{
    private IngredientFixtures $ingredientFixtures;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($this->ingredientFixtures);
    }

    private function getDocument(AggregateRootId $id, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API,
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('ingredient_get_instance', ['id' => $id->toString()]),
            [],
            [],
            $headers
        );
    }

    #[Test]
    public function get_ingredient():void
    {
        $id = $this->ingredientFixtures->testData[0]->getId();
        $this->getDocument($id);

        $document = $this->assertJsonApiGetResponse(Response::HTTP_OK, IngredientResource::TYPE);
        $this->assertEquals($this->ingredientFixtures->testData[0]->getId()->toString(), $document->data->id);
        $this->assertEquals($this->ingredientFixtures->testData[0]->getName(), $document->data->attributes->name);
        $this->assertObjectHasProperty('ingredientTypeId', $document->data->attributes);
        $this->assertObjectHasProperty('description', $document->data->attributes);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function get_ingredient_fails_when_id_is_not_found():void
    {
        $id = AggregateRootId::generateId();
        $this->getDocument($id);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function get_ingredient_fails_when_no_token_present():void
    {
        $id = $this->ingredientFixtures->testData[0]->getId();
        $this->getDocument($id, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
