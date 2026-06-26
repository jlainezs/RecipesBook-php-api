<?php

namespace App\Season\Application\Command\Season;

use App\IngredientType\Application\Command\IngredientType\IngredientTypeUpdateCommand;
use App\IngredientType\Domain\Exceptions\IngredientTypeEmptyNameException;
use App\IngredientType\Domain\Exceptions\IngredientTypeNotFoundException;
use App\IngredientType\Domain\Repository\IngredientTypeRepositoryInterface;
use App\Season\Domain\Exceptions\SeasonEmptyNameException;
use App\Season\Domain\Exceptions\SeasonNotFoundException;
use App\Season\Domain\Repository\SeasonRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class SeasonUpdateHandler
{
    public function __construct(private readonly SeasonRepositoryInterface $repository, private readonly LoggerInterface $logger)
    {}

    /**
     * @throws SeasonNotFoundException
     * @throws SeasonEmptyNameException
     */
    public function __invoke(SeasonUpdateCommand $command): void
    {
        if ($season = $this->repository->findOne($command->id))
        {
            $season->rename($command->name);
            $this->repository->save($season);
            $this->logger->notice(sprintf('Season "%s" updated', $command->id));
        }
        else
        {
            $this->logger->notice(sprintf('Season "%s" not found', $command->id));
            throw new SeasonNotFoundException($command->id);
        }
    }
}
