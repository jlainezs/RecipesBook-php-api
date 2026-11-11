<?php
namespace App\Tests\Functional\Recipe\Presentation\Http\Controller;

use App\Recipe\Domain\Repository\RecipeRepositoryInterface;
use App\Recipe\Presentation\Http\Resource\RecipeResource;
use App\Tests\DataFixtures\Recipe\RecipeFixtures;
use App\Tests\DataFixtures\Security\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GetRecipesControllerTest extends AppApiTestCase
{
    public RecipeFixtures $fixtures;

    public RecipeRepositoryInterface $recipeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->fixtures = new RecipeFixtures();
        $this->loadFixture($this->fixtures);
        $this->recipeRepository = $this->container->get(RecipeRepositoryInterface::class);
    }

    private function getDocument(int $offset = 0, int $limit = 10, bool $sendAuth = true): void
    {
        $headers = ['Accept' => 'application/vnd.api+json'];
        $params = [
            'page' => [
                'offset' => $offset,
                'limit' => $limit,
            ],
        ];

        if ($sendAuth)
        {
            $headers['HTTP_Authorization'] = 'Bearer ' . $this->userToken;
        }

        $this->client->request(
            'GET',
            $this->getDefinedUrl('get_recipes_list', $params),
            [],[],
            $headers
        );
    }

    #[Test]
    public function it_should_return_a_list_of_recipes():void
    {
        $this->getDocument();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('Content-Type', 'application/vnd.api+json');
        $response = json_decode($this->client->getResponse()->getContent());
        $this->assertNotEmpty($response);
        $this->assertObjectHasProperty('data', $response);
        $this->assertSameSize($this->fixtures->testData, $response->data);

    }

    #[Test]
    public function it_should_fail_when_not_authorized(): void
    {
        $this->getDocument(0, 10, false);
        $this->assertJsonApiErrorResponse(Response::HTTP_UNAUTHORIZED, ['header' => 'Authorization']);
    }
}
