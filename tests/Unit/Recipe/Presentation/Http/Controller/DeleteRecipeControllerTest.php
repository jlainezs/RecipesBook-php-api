<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeDelete\DeleteRecipeCommand;
use App\Recipe\Application\Command\RecipeDelete\DeleteRecipeCommandFactory;
use App\Recipe\Application\Command\RecipeDelete\DeleteRecipeDto;
use App\Recipe\Presentation\Http\Controller\DeleteRecipeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteRecipeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $cmdFactory = $this->createMock(DeleteRecipeCommandFactory::class);
        $id = AggregateRootId::generateId()->toString();

        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (DeleteRecipeCommand $command) => $command->id === $id
            ));
        $cmdFactory
            ->expects($this->once())
            ->method('createFromDto')
            ->withAnyParameters()
            ->willReturn(new DeleteRecipeCommand($id));

        $controller = new DeleteRecipeController($commandBus, $cmdFactory);
        $response = $controller(new DeleteRecipeDto($id));

        $this->assertEquals(204, $response->getStatusCode());
    }
}
