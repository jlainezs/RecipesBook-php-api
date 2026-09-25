<?php
namespace App\Shared\Infrastructure\Console;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Style\SymfonyStyle;

final class RecipeBookStyle extends SymfonyStyle
{
    private const int DEFAULT_MAX_PASSWORD_TRIES = 3;

    /**
     * Asks for a hidden password and its confirmation until both match.
     *
     * @throws RuntimeException When the passwords don't match after $maxTries attempts.
     */
    public function askPassword(
        string $question = 'Password',
        string $confirmation = 'Confirm password',
        int $maxTries = self::DEFAULT_MAX_PASSWORD_TRIES
    ): string
    {
        for ($try = 1; $try <= $maxTries; $try++)
        {
            $password = $this->askHidden($question);
            $passwordConfirmation = $this->askHidden($confirmation);

            if ($password === $passwordConfirmation)
            {
                return $password;
            }

            if ($try < $maxTries)
            {
                $this->warning(sprintf('Passwords do not match (%d/%d).', $try, $maxTries));
            }
        }

        throw new RuntimeException('Maximum number of password tries reached.');
    }
}
