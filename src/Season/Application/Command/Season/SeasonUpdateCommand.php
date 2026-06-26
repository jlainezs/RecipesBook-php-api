<?php

namespace App\Season\Application\Command\Season;
final readonly class SeasonUpdateCommand
{
    public function __construct(public readonly string $id, public readonly string $name)
    {}
}
