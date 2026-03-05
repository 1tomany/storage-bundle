<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Contract\Response\UploadedFileResponseInterface;
use OneToMany\StorageBundle\Request\UploadRequest;

interface UploadActionInterface
{
    public function act(UploadRequest $request): UploadedFileResponseInterface;
}
