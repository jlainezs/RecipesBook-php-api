<?php
namespace App\Shared\Application\Service;

interface ApplicationDataValidator
{
    function validate($object): void;
}
