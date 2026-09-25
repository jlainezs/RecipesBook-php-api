<?php
namespace App\Security\Infrastructure\Console;

use App\Security\Application\Command\User\CreateUser\CreateUserCommand;
use App\Security\Domain\Model\UserRole;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Infrastructure\Console\RecipeBookStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'rb:users:create-admin',
    description: 'Create a new user with the role ROLE_ADMIN.'
)]
final class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApplicationDataValidator $validator
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new RecipeBookStyle($input, $output);
        $firstName = $io->ask('First name');
        $lastName = $io->ask('Last name');
        $email = $io->ask('Email');
        $roles = [UserRole::SUPER_ADMIN];

        try
        {
            $password = $io->askPassword();

            $cmd = new CreateUserCommand(
                $email,
                $password,
                $firstName,
                $lastName,
                $roles,
            );
            $this->validator->validate($cmd);
            $this->commandBus->dispatch($cmd);
        }
        catch (Throwable $t)
        {
            $io->error($t->getMessage());
            return Command::FAILURE;
        }

        $io->success('Admin user created successfully');
        return Command::SUCCESS;
    }
}
