<?php
namespace App\Tests\Functional\IngredientType\Presentation\Http\Controller;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\Tests\DataFixtures\Ingredient\IngredientTypeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetIngredientTypeControllerTest extends AppApiTestCase
{
    private IngredientTypeFixtures $additionalIngredientTypeFixtures;
    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->additionalIngredientTypeFixtures = new IngredientTypeFixtures();
        $this->loadFixture($this->additionalIngredientTypeFixtures);
    }

    public function testGetIngredientType()
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_ingredient_type', ['id' => $this->additionalIngredientTypeFixtures->testIngredientTypes[0]->getId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseIsSuccessful();
        $ingredientTypeDto = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(
            $this->additionalIngredientTypeFixtures->testIngredientTypes[0]->getId()->toString(),
            $ingredientTypeDto['ingredientType']['id']
        );
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function testGetIngredientTypeNotFound()
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_ingredient_type', ['id' => AggregateRootId::generateId()->toString()]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetIngredientTypeUnauthorizedFails()
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_ingredient_type', ['id' => AggregateRootId::generateId()->toString()]),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    #[DataProvider('wrongIds')]
    public function testGetIngredientTypeWithWrongIdFails(string $id)
    {
        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_ingredient_type', ['id' => $id]),
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $this->userToken,
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public static function wrongIds(): iterable
    {
        yield 'wrong' => ['This-Is-Wrong-Id'];
    }
}
