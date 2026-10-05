<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeCommand;
use App\IngredientType\Application\Command\IngredientType\CreateIngredientType\CreateIngredientTypeDto;
use App\IngredientType\Application\Query\IngredientType\GetIngredientType\GetIngredientTypeQuery;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\CreateIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateIngredientTypeControllerTest extends TestCase
{
    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201(): void
    {
        $commandBus = $this->createMock(CommandBus::class);
        $queryBus = $this->createMock(QueryBus::class);
        $validator = $this->createMock(ApplicationDataValidator::class);
        $ingredientType = IngredientType::create(AggregateRootId::generateId()->toString(), 'name');

        $dispatchedId = null;
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with(
                $this->callback(
                function (CreateIngredientTypeCommand $cmd) use ($ingredientType) {
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function(CreateIngredientTypeCommand $cmd) use ($ingredientType, &$dispatchedId) {
                    $dispatchedId = $cmd->id;
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(
                function(GetIngredientTypeQuery $query) use (&$dispatchedId) {
                    return $query->id === $dispatchedId;
                }
            ));

        $controller = new CreateIngredientTypeController($commandBus, $queryBus, $validator);
        $request = new CreateIngredientTypeDto(
            name: $ingredientType->getName()->value()
        );
        $response = $controller($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}
