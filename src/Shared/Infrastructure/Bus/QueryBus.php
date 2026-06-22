<?php
namespace App\Shared\Infrastructure\Bus;
interface QueryBus
{
    public function ask(object $query): mixed;
}
