<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface DownloadFileRequestInterface
{
    public const string PREFIX = '__1n__file_';

    /**
     * The directory to download the file into. If the directory
     * provided when calling `setDirectory()` does not exist or
     * is not writable, the default temp directory should be used.
     *
     * @return non-empty-string
     */
    public function getDirectory(): string;

    public function setDirectory(?string $directory): static;

    /**
     * The key of the remote file to download.
     *
     * @return non-empty-string
     */
    public function getKey(): string;
}
