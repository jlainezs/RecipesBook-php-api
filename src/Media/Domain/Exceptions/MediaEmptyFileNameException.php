<?php
namespace App\Media\Domain\Exceptions;

use Exception;
class MediaEmptyFileNameException extends Exception
{
    public function __construct()
    {
        parent::__construct("Media file name cannot be empty", 0);
    }
}
