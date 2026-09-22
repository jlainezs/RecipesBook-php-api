<?php
namespace App\Security\Application\Service;

use App\Security\Domain\Model\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class ApplicationPasswordHasher
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ){}

    public function hash(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword($user, $plainPassword);
    }
}
