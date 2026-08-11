<?php
namespace App\Tests\Unit\Recipe\Presentation\Http\Controller;

use App\Recipe\Application\Command\RecipeDelete\RecipeDeleteCommand;
use App\Recipe\Presentation\Http\Controller\DeleteRecipeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\ValueObject\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class DeleteRecipeControllerTest extends TestCase
{
    #[Test]
    public function it_validates_the_request_and_returns_204(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $id = AggregateRootId::generateId()->toString();

        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (RecipeDeleteCommand $command) => $command->id === $id
            ));
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (RecipeDeleteCommand $command) => $command->id === $id
            ));
        $controller = new DeleteRecipeController($commandBus, $validator);
        $request = Request::create(
            uri: '/api/v1/recipes/' . $id,
            method: 'DELETE',
            server: ['Content-Type' => 'application/json']
        );
        $request->attributes->add(['id' => $id]);

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
