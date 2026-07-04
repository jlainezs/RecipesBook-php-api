<?php
namespace App\UnitOfMeasure\Application\Command\UnitOfMeasure;

use App\UnitOfMeasure\Domain\Exceptions\UnitOfMeasureNotFoundException;
use App\UnitOfMeasure\Domain\Repository\UnitOfMeasureRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UnitOfMeasureDeleteHandler
{
    public function __construct(private UnitOfMeasureRepositoryInterface $repository)
    {}

    /**
     * @throws UnitOfMeasureNotFoundException
     */
    public function __invoke(UnitOfMeasureDeleteCommand $command): void
    {
        if ($uom = $this->repository->findOne($command->id))
        {
            $this->repository->delete($uom);
        } else {
            throw new UnitOfMeasureNotFoundException($command->id);
        }
    }
}
