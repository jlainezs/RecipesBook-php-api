<?php

namespace App\Media\Domain\Storage;
interface MediaStorageInterface
{
    public function upload(string $path, string $content): void;
    public function download(string $path): string;
    public function delete(string $path): void;
}
