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

    public const string USER_NAME = 'test@example.com';
    public const string PASSWORD = 'test';
    public const string FIRST_NAME = 'First';
    public const string LAST_NAME = 'Last';

    /**
     * @throws EmptyIdNotAllowedException
     */
    public function load(ObjectManager $manager): void
    {
        $user = User::create(
            self::USER_NAME,
            self::PASSWORD,
            self::FIRST_NAME,
            self::LAST_NAME,
            [UserRole::USER]
        );
        $user->changePassword($this->userPasswordHasher->hashPassword($user, self::PASSWORD));
        $manager->persist($user);
        $manager->flush();
    }
}
