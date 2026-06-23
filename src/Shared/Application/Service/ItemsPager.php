<?php
namespace App\Shared\Application\Service;
interface ItemsPager
{
    public function items(int $offset = 0, int $limit = 20): array;
}
