<?php
namespace App\Tests\Unit\Security\Preserntation\Http\Controller;

use App\Security\Application\Command\User\CreateUser\CreateUserCommand;
use App\Security\Application\Command\User\CreateUser\CreateUserCommandFactory;
use App\Security\Application\Command\User\CreateUser\CreateUserDto;
use App\Security\Domain\Model\UserRole;
use App\Security\Presentation\Http\Controller\CreateUserController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateUserControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $commandFactory = $this->createMock(CreateUserCommandFactory::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);

        $id = AggregateRootId::generateId()->toString();
        $request = new CreateUserDto(
            'eml@eml.com',
            'password',
            'John',
            'Doe',
            [UserRole::USER]
        );

        $commandFactory->expects($this->once())
            ->method('createFromDto')
            ->with($request)
            ->willReturn(new CreateUserCommand(
                $id,
                $request->email,
                $request->password,
                $request->firstName,
                $request->lastName,
                $request->roles
            ));

        $commandBus->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn(CreateUserCommand $cmd) => $cmd->email === $request->email
            ));

        $urlGenerator->expects($this->once())
            ->method('generate')
            ->withAnyParameters();

        $controller = new CreateUserController($commandBus, $commandFactory, $urlGenerator);
        $response = $controller($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
