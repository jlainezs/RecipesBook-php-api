<?php
namespace App\Tests\Unit\IngredientType\Presentation\Http\Controller;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeUpdateCommand;
use App\IngredientType\Domain\Model\IngredientType;
use App\IngredientType\Presentation\Http\Controller\PutIngredientTypeController;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class IngredientTypeUpdateControllerTest extends TestCase
{
    private CommandBus $commandBus;
    private ApplicationDataValidator $validator;

    public function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_should_update_ingredient_type(): void
    {
        $ingredientType = IngredientType::create('name');
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                function (IngredientTypeUpdateCommand $cmd) use ($ingredientType) {
                    return $cmd->name === $ingredientType->getName()->value();
                }
            ));
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                function (IngredientTypeUpdateCommand $cmd) use ($ingredientType) {
                    return $cmd->id === $ingredientType->getId()->toString();
                }
            ));
        $controller = new PutIngredientTypeController($this->commandBus, $this->validator);
        $payload = ['name' => $ingredientType->getName()->value()];
        $request = Request::create(
            uri: '/ingredient-types/' . $ingredientType->getId()->toString(),
            method: 'PUT',
            server: ['Content-Type' => 'application/json'],
            content: json_encode($payload)
        );
        $request->attributes->set('id', $ingredientType->getId()->toString());

        $response = $controller($request);

        $this->assertEquals(204, $response->getStatusCode());
    }
}
