<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface DownloadFileRequestInterface
{
    public const string PREFIX = '__1n__file_';

    /**
     * @return non-empty-string
     */
    public function getDirectory(): string;

    /**
     * Download the file to this directory.
     */
    public function setDirectory(?string $directory): static;

    /**
     * The key of the remote storage file.
     *
     * @return non-empty-string
     */
    public function getKey(): string;
}
