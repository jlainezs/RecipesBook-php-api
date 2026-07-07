<?php
namespace App\Media\Domain\Exceptions;

use InvalidArgumentException;

class MediaEmptyFileNameException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Media file name cannot be empty", 0);
    }
}
