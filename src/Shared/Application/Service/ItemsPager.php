<?php
namespace App\Shared\Application\Service;
interface ItemsPager
{
    public function items($offset = 0, $limit = 20): array;
}
