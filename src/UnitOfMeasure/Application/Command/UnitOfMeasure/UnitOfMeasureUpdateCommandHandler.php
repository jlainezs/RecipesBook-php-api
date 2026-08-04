<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\Shared\Domain\Exception\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObject\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptyNameException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureEmptySymbolException;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Infrastructure\Repository\UnitOfMeasureRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class UnitOfMeasureUpdateCommandHandler
{
    public function __construct(private UnitOfMeasureRepository $repository)
    {}

    /**
     * @throws UnitOfMeasureEmptyNameException
     * @throws UnitOfMeasureEmptySymbolException
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(UnitOfMeasureUpdateCommand $command): void
    {
        if ($uom = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $uom->rename($command->name);
            $uom->changeSymbol($command->symbol);
            $uom->setUomType($command->unitOfMeasureEnum);
            $this->repository->save($uom);
        }
        else
        {
            throw new UnitOfMeasureNotFoundException($command->id);
        }
    }

}
