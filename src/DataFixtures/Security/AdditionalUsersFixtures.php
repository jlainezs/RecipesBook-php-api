<?php
namespace App\DataFixtures\Security;

use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdditionalUsersFixtures extends Fixture
{
    public array $testUsers;

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ){
        $this->testUsers[] = User::create(
            'user1@example.com',
            'xxx',
            'One',
            'User',
            [UserRole::USER]
        );
        $this->testUsers[] = User::create(
            'user2@example.com',
            'yyy',
            'Two',
            'User',
            [UserRole::USER]
        );
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->testUsers as $user)
        {
            $user->changePassword($this->userPasswordHasher->hashPassword($user, $user->getPassword()));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
