<?php declare(strict_types=1);

namespace Ihor\Frame2\Service;

use League\Flysystem\FilesystemOperator;

class ExampleFilesystemService
{
    public function __construct(
        private readonly FilesystemOperator $fileSystemPublic,
        private readonly FilesystemOperator $fileSystemPrivate
    ) {
    }

    public function readPrivateFile(string $filename) {
        return $this->fileSystemPrivate->read($filename);
    }

    public function writePrivateFile(string $filename, string $content) {
        $this->fileSystemPrivate->write($filename, $content);
    }

    public function listPublicFiles(): array
    {
        return $this->fileSystemPublic->listContents('', true)->toArray();
    }

    public function listPrivateFiles(): array
    {
        return $this->fileSystemPrivate->listContents('', true)->toArray();
    }
}