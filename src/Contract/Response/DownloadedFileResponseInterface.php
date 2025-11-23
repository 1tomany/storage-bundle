<?php

namespace OneToMany\StorageBundle\Contract\Response;

interface DownloadedFileResponseInterface
{
    /**
     * The absolute path to the downloaded file.
     *
     * @return non-empty-string
     */
    public function getPath(): string;
}
