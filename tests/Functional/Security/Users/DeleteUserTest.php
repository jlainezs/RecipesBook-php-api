<?php
namespace App\Tests\Functional\Security\Users;

use App\DataFixtures\LoginFixtures;
use App\Tests\Functional\AppApiTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DeleteUserTest extends AppApiTestCase
{
    protected string $userToken;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadFixture(new LoginFixtures($this->container->get(UserPasswordHasherInterface::class)));
        $this->loginWithUser(LoginFixtures::USER_NAME, LoginFixtures::PASSWORD);
        $this->userToken = $this->getAuthToken();
    }

}
