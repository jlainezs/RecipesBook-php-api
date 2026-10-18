<?php
namespace App\Tests\Unit\UnitOfMeasure\Presentation\Http\Controller;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Service\ApplicationDataValidator;
use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureCommandFactory;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureDto;
use App\UnitOfMeasure\Application\Command\UnitOfMeasure\CreateUnitOfMeasure\CreateUnitOfMeasureCommand;
use App\UnitOfMeasure\Presentation\Http\Controller\CreateUnitOfMeasureController;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateUnitOfMeasureControllerTest extends TestCase
{
    public CommandBus $commandBus;
    public CreateUnitOfMeasureCommandFactory $commandFactory;
    public UrlGeneratorInterface $urlGenerator;
    public ApplicationDataValidator $validator;


    protected function setUp(): void
    {
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->validator = $this->createMock(ApplicationDataValidator::class);
        $this->commandFactory = new CreateUnitOfMeasureCommandFactory($this->validator);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
    }

    /**
     * @throws EmptyIdNotAllowedException
     */
    #[Test]
    public function it_dispatches_command_and_returns_201_response(): void
    {
        $dto = new CreateUnitOfMeasureDto(
            name: 'test',
            symbol: 't',
            unitOfMeasureType: 2
        );
        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($this->callback(
                fn (CreateUnitOfMeasureCommand $cmd) => $dto->name === $cmd->name
            ));
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(
                fn (CreateUnitOfMeasureCommand $cmd) => $dto->name === $cmd->name
            ));
        $this->urlGenerator
            ->expects($this->once())
            ->method('generate')
            ->withAnyParameters();

        $controller = new CreateUnitOfMeasureController(
            $this->commandBus,
            $this->commandFactory,
            $this->urlGenerator
        );
        $response = $controller($dto);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
