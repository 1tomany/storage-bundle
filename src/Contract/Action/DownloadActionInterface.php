<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Request\DownloadRequest;
use OneToMany\StorageBundle\Response\DownloadResponse;

interface DownloadActionInterface
{
    public function act(DownloadRequest $request): DownloadResponse;
}
