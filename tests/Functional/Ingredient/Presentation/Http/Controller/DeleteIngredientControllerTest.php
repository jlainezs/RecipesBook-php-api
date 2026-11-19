<?php
namespace App\Tests\Functional\Ingredient\Presentation\Http\Controller;

use App\Ingredient\Domain\Repository\IngredientRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientFixtures;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteIngredientControllerTest extends AppApiTestCase
{
    private IngredientFixtures $ingredientFixtures;
    private IngredientRepositoryInterface $repository;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $additionalIngredientTypeFixtures = new IngredientTypeFixtures();
        $this->ingredientFixtures = new IngredientFixtures();
        $this->loadFixture($additionalIngredientTypeFixtures);
        $this->loadFixture($this->ingredientFixtures);
        $this->repository = $this->container->get(IngredientRepositoryInterface::class);
    }

    private function deleteDocument(AggregateRootId $id, bool $sendAuth = true): void
    {
        $headers = [
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
        ];

        if ($sendAuth) {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'DELETE',
            $this->getDefinedUrl('ingredient_delete', ['id' => $id->toString()]),
            [],
            [],
           $headers
        );
    }

    #[Test]
    public function it_deletes_ingredient(): void
    {
        $ingredientId = $this->ingredientFixtures->testData[0]->getId();
        $this->deleteDocument($ingredientId);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $ingredient = $this->repository->findOne($ingredientId);
        $this->assertNull($ingredient);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_ingredient_not_found(): void
    {
        $ingredientId = AggregateRootId::generateId();
        $this->deleteDocument($ingredientId);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_when_ingredient_not_authorized(): void
    {
        $ingredientId = AggregateRootId::generateId();
        $this->deleteDocument($ingredientId, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
