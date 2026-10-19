<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
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
    private IngredientTypeFixtures $additionalIngredientTypeFixtures;
    private IngredientTypeRepositoryInterface $ingredientTypeRepository;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientTypeFixtures = new IngredientTypeFixtures();
        $this->loadFixture($this->additionalIngredientTypeFixtures);
        $this->ingredientTypeRepository = $this->container->get(IngredientTypeRepositoryInterface::class);
    }

    #[Test]
    public function update_ingredient_type(): void
    {
        $toUpdate = $this->ingredientTypeRepository->findOne($this->additionalIngredientTypeFixtures->testIngredientTypes[0]->getId());
        $this->assertNotNull($toUpdate);

        $this->client->request(
            'PUT',
            $this->getDefinedUrl('ingredient_types_update_instance', ['id' => $toUpdate->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'name' => 'updated',
            ])
        );
        $this->assertResponseIsSuccessful();
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
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('ingredient_types_update_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'name' => 'updated',
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_not_authenticated(): void
    {
        $id = AggregateRootId::generateId();
        $this->client->request(
            'PUT',
            $this->getDefinedUrl('ingredient_types_update_instance', ['id' => $id->toString()]),
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ],
            json_encode([
                'name' => 'updated',
            ])
        );
        $this->assertProblemDetailsResponse(Response::HTTP_UNAUTHORIZED);
    }
}
