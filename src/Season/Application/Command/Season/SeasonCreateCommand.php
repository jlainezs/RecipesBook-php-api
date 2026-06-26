<?php

namespace App\Season\Application\Command\Season;

readonly final class SeasonCreateCommand
{
    public function __construct(public readonly string $name)
    {}
}
