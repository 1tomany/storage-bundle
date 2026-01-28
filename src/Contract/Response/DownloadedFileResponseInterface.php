<?php

namespace OneToMany\StorageBundle\Contract\Response;

interface DownloadedFileResponseInterface
{
    /**
     * @return non-empty-string
     */
    public function getPath(): string;
}
