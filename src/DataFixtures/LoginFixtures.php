<?php
namespace App\DataFixtures;

use App\Security\Domain\Model\User;
use App\Security\Domain\Model\UserRole;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ){}

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $user = User::create(
            'test@example.com',
            'XXX',
            'First',
            'Last',
            [UserRole::USER]
        );
        $user->changePassword($this->userPasswordHasher->hashPassword($user, 'test'));
        $manager->persist($user);
        $manager->flush();
    }
}
