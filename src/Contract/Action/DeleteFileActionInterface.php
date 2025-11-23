<?php

namespace OneToMany\StorageBundle\Contract\Action;

use OneToMany\StorageBundle\Contract\Request\DeleteFileRequestInterface;
use OneToMany\StorageBundle\Contract\Response\DeletedFileResponseInterface;

interface DeleteFileActionInterface
{
    public function act(DeleteFileRequestInterface $request): DeletedFileResponseInterface;
}
