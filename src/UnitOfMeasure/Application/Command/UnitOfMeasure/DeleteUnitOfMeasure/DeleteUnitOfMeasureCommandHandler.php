<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure\DeleteUnitOfMeasure;

use App\Shared\Domain\Exceptions\EmptyIdNotAllowedException;
use App\Shared\Domain\ValueObjects\AggregateRootId;
use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteUnitOfMeasureCommandHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureNotFoundException
     * @throws EmptyIdNotAllowedException
     */
    public function __invoke(DeleteUnitOfMeasureCommand $command): void
    {
        if ($uom = $this->repository->findOne(new AggregateRootId($command->id)))
        {
            $this->repository->delete($uom);
        } else {
            throw new UnitOfMeasureNotFoundException($command->id);
        }
    }
}
