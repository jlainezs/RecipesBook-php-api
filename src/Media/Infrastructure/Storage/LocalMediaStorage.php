<?php

namespace App\Media\Infrastructure\Storage;

use App\Media\Domain\Storage\MediaStorageInterface;

final readonly class LocalMediaStorage implements MediaStorageInterface
{
    public function upload(string $path, string $content): void
    {
        // TODO: Implement upload() method.
    }

    public function download(string $path): string
    {
        // TODO: Implement download() method.
    }

    public function delete(string $path): void
    {
        // TODO: Implement delete() method.
    }
}
