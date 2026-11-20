<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Recipe\RecipeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteRecipeControllerTest extends AppApiTestCase
{
    public RecipeFixtures $fixtures;

    public RecipeRepositoryInterface $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new RecipeFixtures();
        $this->loadFixture($this->fixtures);
        $this->repository = $this->container->get(RecipeRepositoryInterface::class);
    }

    private function deleteDocument(AggregateRootId $id, bool $sendAuth = true): void
    {
        $headers = [
            'CONTENT_TYPE' => self::CONTENT_TYPE_HEADER_JSON_API,
            'HTTP_ACCEPT' => self::ACCEPT_HEADER_JSON_API
        ];

        if ($sendAuth)
        {
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            "DELETE",
            $this->getDefinedUrl('delete_recipe', ['id' => $id->toString()]),
            [],
            [],
            $headers
        );

    }

    #[Test]
    public function it_should_delete_recipe():void
    {
        $recipeToDelete = $this->fixtures->testData[0];
        $this->deleteDocument($recipeToDelete->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        // ensure recipe is deleted
        $recipe = $this->repository->findOne($recipeToDelete->getId());
        $this->assertNull($recipe);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_if_recipe_is_not_found(): void
    {
        $idToDelete = AggregateRootId::generateId();
        $this->deleteDocument($idToDelete);
        $this->assertJsonApiErrorResponse(Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_fails_if_not_authorized(): void
    {
        $idToDelete = AggregateRootId::generateId();
        $this->deleteDocument($idToDelete, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED);
    }
}
