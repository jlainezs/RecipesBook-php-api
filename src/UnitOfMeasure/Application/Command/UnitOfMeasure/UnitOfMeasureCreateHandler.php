<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptyNameException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Model\UnitOfMeasure;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UnitOfMeasureCreateHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureEmptySymbolException
     * @throws UnitOfMeasureEmptyNameException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(UnitOfMeasureCreateCommand $command): void
    {
        $uom = UnitOfMeasure::create($command->name, $command->symbol, $command->unitOfMeasureEnum);
        $this->repository->save($uom);
    }
}
