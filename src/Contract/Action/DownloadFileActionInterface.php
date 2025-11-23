<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Contract\Request\DownloadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DownloadedFileResponseInterface;

interface DownloadFileActionInterface
{
    public function act(DownloadFileRequestInterface $request): DownloadedFileResponseInterface;
}
