<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface DownloadFileRequestInterface
{
    public const string PREFIX = '__1n__file_';

    /**
     * @return non-empty-string
     */
    public function getKey(): string;

    /**
     * @return non-empty-string
     */
    public function getDirectory(): string;

    public function setDirectory(?string $directory): static;
}
