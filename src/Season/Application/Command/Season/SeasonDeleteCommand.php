<?php

namespace App\Season\Application\Command\Season;

readonly final class SeasonDeleteCommand
{
    public function __construct(public readonly string $id)
    {}
}
