<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface DeleteFileRequestInterface
{
    /**
     * @return non-empty-string
     */
    public function getKey(): string;
}
