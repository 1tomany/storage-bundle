<?php

namespace OneToMany\StorageBundle\Contract\Response;

interface UploadedFileResponseInterface
{
    /**
     * @return non-empty-string
     */
    public function getUrl(): string;
}
