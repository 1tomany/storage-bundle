<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;
use OneToMany\StorageBundle\Request\DownloadRequest;

interface DownloadActionInterface
{
    public function act(DownloadRequest $request): DownloadedFileResponseInterface;
}
