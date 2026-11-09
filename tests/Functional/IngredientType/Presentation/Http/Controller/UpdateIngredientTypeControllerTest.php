<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\IngredientType\Presentation\Http\Resource\IngredientTypeResource;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateIngredientTypeControllerTest extends AppApiTestCase
{
    private IngredientTypeFixtures $fixtures;
    private IngredientTypeRepositoryInterface $ingredientTypeRepository;

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
        $this->ingredientTypeRepository = $this->container->get(IngredientTypeRepositoryInterface::class);
    }

    private function patchDocument(AggregateRootId $id, array $document, bool $sendAuth = true)
    {
        $contentType = 'application/vnd.api+json';
        $accept = 'application/vnd.api+json';
        $headers = [
            'CONTENT_TYPE' => $contentType,
            'HTTP_ACCEPT' => $accept,
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'PATCH',
            $this->getDefinedUrl('ingredient_types_update_instance', ['id' =>$id->toString()]),
            [],
            [],
            $headers,
            json_encode($document)
        );
    }

    #[Test]
    public function it_updates_an_ingredient_type(): void
    {
        $toUpdate = $this->ingredientTypeRepository->findOne($this->fixtures->testData[0]->getId());
        $this->assertNotNull($toUpdate);

        $updatedData = [
            'data' => [
                'type' => IngredientTypeResource::TYPE,
                'id' => $toUpdate->getId()->toString(),
                'attributes' => [
                    'name' => 'updated',
                ]
            ]
        ];
        $this->patchDocument($toUpdate->getId(), $updatedData);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $updated = $this->ingredientTypeRepository->findOne($toUpdate->getId());
        $this->assertNotNull($updated);
        $this->assertEquals('updated', $updated->getName()->value());
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_id_not_found(): void
    {
        $id = AggregateRootId::generateId();
        $updatedData = [
            'data' => [
                'type' => IngredientTypeResource::TYPE,
                'id' => $id->getId()->toString(),
                'attributes' => [
                    'name' => 'updated',
                ]
            ]
        ];

        $this->patchDocument($id, $updatedData);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authenticated(): void
    {
        $id = AggregateRootId::generateId();
        $this->patchDocument($id, [], false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
