<?php

namespace OneToMany\StorageBundle\Contract\Request;

interface RequestInterface
{
    /**
     * The prefix for all uploaded and downloaded files.
     *
     * @var non-empty-lowercase-string
     */
    public const string FILE_PREFIX = 'onetomany_storage_';
}
