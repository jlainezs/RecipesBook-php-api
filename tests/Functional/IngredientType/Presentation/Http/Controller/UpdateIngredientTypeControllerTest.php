<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\DataFixtures\Ingredient\AdditionalIngredientTypeFixtures;
use App\DataFixtures\Security\LoginFixtures;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateIngredientTypeControllerTest extends AppApiTestCase
{
    private AdditionalIngredientTypeFixtures $additionalIngredientTypeFixtures;
    private IngredientTypeRepositoryInterface $ingredientTypeRepository;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientTypeFixtures = new AdditionalIngredientTypeFixtures();
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

    }
}
