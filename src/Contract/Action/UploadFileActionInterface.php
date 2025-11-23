<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Contract\Request\UploadFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;

interface UploadFileActionInterface
{
    public function act(UploadFileRequestInterface $request): UploadedFileResponseInterface;
}
