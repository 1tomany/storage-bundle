<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Request\UploadRequest;
use OneToMany\StorageBundle\Response\UploadResponse;

interface UploadActionInterface
{
    public function act(UploadRequest $request): UploadResponse;
}
