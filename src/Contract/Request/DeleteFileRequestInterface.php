<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface DeleteFileRequestInterface
{
    /**
     * The key of the remote file to delete.
     *
     * @return non-empty-string
     */
    public function getKey(): string;
}
